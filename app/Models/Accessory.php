<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    use HasMediaUrls;
    protected $table = 'accessories';

    public $timestamps = false;

    protected $guarded = [];
}
