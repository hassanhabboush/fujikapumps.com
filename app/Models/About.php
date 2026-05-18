<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasMediaUrls;
    protected $table = 'about';
    protected $guarded = [];
}
