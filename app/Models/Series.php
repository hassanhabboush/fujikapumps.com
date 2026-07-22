<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory, HasMediaUrls;

    protected $table = 'series';

    public $timestamps = false;

    protected $guarded = [];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }
}
