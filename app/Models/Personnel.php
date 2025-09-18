<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    use HasFactory;

    protected $table = 'personnel';          // ชื่อตาราง
    protected $primaryKey = 'personnel_id';   // primary key
    const CREATED_AT = 'personnel_date_create';
    const UPDATED_AT = 'personnel_date_update';

    public function scopeActive($query)
    {
        return $query->where('personnel_display', 'A');
    }
}
