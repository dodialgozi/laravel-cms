<?php

namespace App\Models;

use Exception;
use App\Models\Traits\ModelFunction;
use Google\Service\ShoppingContent\Resource\Pos;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    protected $table = 'user';
    protected $primaryKey = 'user_id';
    protected $perPage = 20;
    protected $guarded = [];

    use HasRoles;
    use ModelFunction;

    public function id()
    {
        return $this->user_id;
    }

    public function getAuthPassword()
    {
        return $this->user_password;
    }

    public function getRememberToken()
    {
        return $this->user_remember_me;
    }

    public function setRememberToken($value)
    {
        $this->user_remember_me = $value;
    }

    public function getRememberTokenName()
    {
        return 'user_remember_me';
    }

    // public function locations()
    // {
    //     return $this->hasMany(Location::class, 'user_id');
    // }

    public function user_categories()
    {
        return $this->hasMany(UserCategory::class, 'user_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'user_category', 'user_id', 'category_id');
    }

    public function userInstances()
    {
        return $this->hasMany(UserInstance::class, 'user_id');
    }

    public function instances()
    {
        return $this->belongsToMany(Instance::class, 'user_instance', 'user_id', 'instance_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'user_id');
    }

    public function allPublishedPost()
    {
        return $this->hasMany(Post::class, 'user_id')
            ->where('post_status', 'publish')
            ->orderBy('post_date');
    }

    public function getPublishedPostAttribute()
    {
        return $this->publishedPost();
    }

    public function publishedPost($tanggalMulai = null, $tanggalSampai = null)
    {
        if (!empty($tanggalMulai) && !empty($tanggalSampai)) {
            $this->load(['allPublishedPost' => function ($query) use ($tanggalMulai, $tanggalSampai) {
                $query->whereRentangTanggal($tanggalMulai, $tanggalSampai);
            }]);
        }

        if (!$this->relationLoaded('allPublishedPost')) throw new Exception('allPublishedPost has not been loaded yet.');

        $publishedPost = $this->allPublishedPost;

        $tanggal = $publishedPost->groupBy(fn ($item) => date('Y-m-d', strtotime($item->post_date)))
            ->map(fn ($item) => $item->count('post_date'));
        $poin = $tanggal->sum();

        return [
            'tanggal' => $tanggal,
            'total' => $poin,
        ];
    }

    public function publishedPostBulan($tahunBulan)
    {
        [$awal, $akhir] = dateRangeInMonth($tahunBulan);
        return $this->publishedPost($awal, $akhir);
    }

    public function getViewPublishedPostAttribute()
    {
        return $this->viewPublishedPost();
    }

    public function viewPublishedPost($tanggalMulai = null, $tanggalSampai = null)
    {
        if (!empty($tanggalMulai) && !empty($tanggalSampai)) {
            $this->load(['allPublishedPost' => function ($query) use ($tanggalMulai, $tanggalSampai) {
                $query->whereRentangTanggal($tanggalMulai, $tanggalSampai);
            }]);
        }

        if (!$this->relationLoaded('allPublishedPost')) throw new Exception('allPublishedPost has not been loaded yet.');

        $publishedPost = $this->allPublishedPost;

        $tanggal = $publishedPost->groupBy(fn ($item) => date('Y-m-d', strtotime($item->post_date)))
            ->map(fn ($item) => $item->sum('post_view'));
        $poin = $tanggal->sum();

        return [
            'tanggal' => $tanggal,
            'total' => $poin,
        ];
    }

    public function getRatingPostAttribute()
    {
        return $this->ratingPost();
    }

    public function ratingPost($tanggalMulai = null, $tanggalSampai = null)
    {
        if (!empty($tanggalMulai) && !empty($tanggalSampai)) {
            $this->load(['allPublishedPost' => function ($query) use ($tanggalMulai, $tanggalSampai) {
                $query->whereRentangTanggal($tanggalMulai, $tanggalSampai);
            }, 'allPublishedPost.rating']);
        }

        if (!$this->relationLoaded('allPublishedPost')) throw new Exception('allPublishedPost has not been loaded yet.');

        $publishedPost = $this->allPublishedPost->reject(fn ($item) => empty($item->post_title));

        $tanggal = $publishedPost->groupBy(fn ($item) => date('Y-m-d', strtotime($item->post_date)))
            ->map(function ($item) {

                $highest = $item->sum('rating.highest');
                $high = $item->sum('rating.high');
                $medium = $item->sum('rating.medium');
                $low = $item->sum('rating.low');
                $lowest = $item->sum('rating.lowest');

                return [
                    'highest' => $highest,
                    'high' => $high,
                    'medium' => $medium,
                    'low' => $low,
                    'lowest' => $lowest,
                ];
            });

        return [
            'tanggal' => $tanggal,
            'total' => [
                'highest' => $tanggal->sum('highest'),
                'high' =>  $tanggal->sum('high'),
                'medium' =>  $tanggal->sum('medium'),
                'low' =>  $tanggal->sum('low'),
                'lowest' =>  $tanggal->sum('lowest'),
            ],
        ];
    }
}
