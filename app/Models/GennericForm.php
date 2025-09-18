<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GennericForm extends Model
{
    use HasFactory;

    protected $table = 'gennericforms';          // ชื่อตาราง
    protected $primaryKey = 'gennericforms';

    public function scopeActive($query)
    {
        return $query->where('gennericforms_display', 'A');
    }
}
