<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Illuminate\Database\Eloquent\Model;

class Instance extends Model
{
    protected $table = 'instance';
    protected $primaryKey = 'instance_id';
    protected $perPage = 20;
    protected $guarded = [];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    use ModelFunction;

    public function userInstances()
    {
        return $this->hasMany(UserInstance::class, 'instance_id', 'instance_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_instance', 'instance_id', 'user_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'instance_id', 'instance_id');
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'instance_id', 'instance_id');
    }

    public function tags()
    {
        return $this->hasMany(Tag::class, 'instance_id', 'instance_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'instance_id', 'instance_id');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'instance_id', 'instance_id');
    }

    public function partners()
    {
        return $this->hasMany(Partner::class, 'instance_id', 'instance_id');
    }

    public function siteSettings()
    {
        return $this->hasMany(SiteSetting::class, 'instance_id', 'instance_id');
    }

    public function lecturers()
    {
        return $this->hasMany(Lecturer::class, 'instance_id', 'instance_id');
    }

    public function alumni()
    {
        return $this->hasMany(Alumnus::class, 'instance_id', 'instance_id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'instance_id', 'instance_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'instance_id', 'instance_id');
    }
}
