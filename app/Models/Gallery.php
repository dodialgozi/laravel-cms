<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'gallery';
    protected $primaryKey = 'gallery_id';
    protected $perPage = 20;
    protected $guarded = [];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    use ModelFunction;

    public function instance()
    {
        return $this->belongsTo(Instance::class, 'instance_id');
    }

    public function images()
    {
        return $this->hasMany(GalleryImage::class, 'gallery_id', 'gallery_id');
    }
}
