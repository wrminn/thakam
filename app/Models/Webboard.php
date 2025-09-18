<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webboard extends Model
{
    use HasFactory;

    protected $table = 'threads';          // ชื่อตาราง
    protected $primaryKey = 'threads_id';   // primary key
    protected $appends = ['threads_date_show_buddhist'];
}
