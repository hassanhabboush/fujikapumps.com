<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory, HasMediaUrls;

    protected $table = 'slider';

    protected $guarded = [];
}
