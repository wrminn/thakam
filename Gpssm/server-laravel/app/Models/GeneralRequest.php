<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralRequest extends Model
{
    use HasFactory;

    protected $table = 'general_requests';

    protected $fillable = [
        'user_id',
        'date',
        'subject',
        'request',
        'prefix',
        'first_name',
        'last_name',
        'age',
        'addr_no',
        'addr_moo',
        'addr_subdistrict',
        'addr_district',
        'addr_province',
        'phone',
        'detail',
        'map_note',
        'attachments',
        'consent',
    ];

    protected $casts = [
        'attachments' => 'array',
        'consent' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'attachments' => '[]',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
