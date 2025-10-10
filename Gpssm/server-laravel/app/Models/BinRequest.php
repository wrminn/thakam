<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinRequest extends Model
{
    use HasFactory;

    protected $table = 'bin_requests';

    protected $fillable = [
        'user_id',
        'date',
        'subject',
        'prefix',
        'full_name',
        'age',
        'phone',
        'email',
        'house_no',
        'moo',
        'road',
        'subdistrict',
        'district',
        'province',
        'postcode',
        'place_type',
        'place_type_other',
        'lat',
        'lng',
        'detail',
        'attachments',
        'consent',
    ];

    protected $casts = [
        'attachments' => 'array',
        'consent' => 'boolean',
        'lat' => 'float',
        'lng' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'attachments' => '[]',
    ];
}
