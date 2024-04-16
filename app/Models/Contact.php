<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    protected $table = 'contact';
    protected $primaryKey = 'contact_id';
    protected $perPage = 20;
    protected $guarded = [];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    use ModelFunction;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
