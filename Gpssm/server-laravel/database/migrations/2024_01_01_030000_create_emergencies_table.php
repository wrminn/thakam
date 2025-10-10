<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Emergency extends Model
{
    use HasFactory;

    protected $table = 'emergencies';

    // ใส่ฟิลด์ที่ "คาดว่า" มีตามสคีมาเดิม (เกินได้ ไม่เป็นไร)
    protected $fillable = [
        'user_id',
        'category',
        'title',
        'reporter_name',
        'phone',
        'description',
        'lat',
        'lng',
        'photo',
    ];

    protected $casts = [
        'photo' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'lat' => 'float',
        'lng' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
