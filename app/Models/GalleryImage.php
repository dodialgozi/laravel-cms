<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    use ModelFunction;
    protected $table = 'gallery_image';
    protected $primaryKey = 'gallery_image_id';
    protected $perPage = 20;
    protected $guarded = [];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    public function gallery()
    {
        return $this->belongsTo(Gallery::class, 'gallery_id', 'gallery_id');
    }
}
