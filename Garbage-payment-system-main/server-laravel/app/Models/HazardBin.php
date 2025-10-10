<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HazardBin extends Model
{
    protected $fillable = ['lat','lng','note'];
    public $timestamps = true;
}
