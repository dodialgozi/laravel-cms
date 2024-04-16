<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    protected $table = 'post_tags';
    protected $primaryKey = 't_id';
    protected $perPage = 20;
    protected $guarded = [];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    use ModelFunction;

    public function tag() {
        return $this->belongsTo(Tag::class, 'tag_id', 'tag_id');
    }

    public function posts() {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

    public function instance() {
        return $this->belongsTo(Instance::class, 'instance_id', 'instance_id');
    }
}
