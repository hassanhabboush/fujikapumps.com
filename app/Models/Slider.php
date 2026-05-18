<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasMediaUrls;
    protected $table = 'slider';

    protected $guarded = [];
}
