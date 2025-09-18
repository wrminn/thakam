<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banner';          // ชื่อตาราง
    protected $primaryKey = 'banner_id';   // primary key

    public function scopeActive($query)
    {
        return $query->where('banner_display', 'A');
    }
}
