<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasMediaUrls;
    protected $table = 'gallery';

    public $timestamps = false;

    protected $guarded = [];
}
