<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'user_id',
        'bill_code',
        'address',
        'amount',
        'status',
        'paid_at',
        'due_month',
        'due_year',
    ];

    protected $casts = [
        'amount' => 'integer',
        'paid_at' => 'datetime',
        'due_month' => 'integer',
        'due_year' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
