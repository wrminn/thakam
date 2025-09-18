<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;

    protected $table = 'slide';          // ชื่อตาราง
    protected $primaryKey = 'slide_id';   // primary key

    public function scopeActive($query)
    {
        return $query->where('slide_display', 'A');
    }
}