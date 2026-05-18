<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasMediaUrls;
    protected $table = 'team';

    public $timestamps = false;

    protected $guarded = [];
}
