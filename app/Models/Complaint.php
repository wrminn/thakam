<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Complaint extends Model
{
    use HasFactory;

    protected $table = 'complaints';          // ชื่อตาราง
    protected $primaryKey = 'complaints_id';   // primary key
    protected $appends = ['complaints_date_show_buddhist'];

    public function scopeActive($query)
    {
        return $query->where('complaints_display', 'A');
    }

    public function getComplaintDateShowBuddhistAttribute()
    {
        if (!$this->complaints_date_insert) {
            return null;
        }

        $date = Carbon::parse($this->complaints_date_insert);
        $thaiYear = $date->year + 543;
        $thaiDate = $date->format('d-m-') . $thaiYear . ' เวลา ' . $date->format('H:i:s');
  
        return $thaiDate;
    }
}
