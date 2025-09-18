<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';          // ชื่อตาราง
    protected $primaryKey = 'categories_id';   // primary key


    public function scopeActive($query)
    {
        return $query->where('categories_display', 'A');
    }
}
