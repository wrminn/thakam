<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Corruption extends Model
{
    use HasFactory;

    protected $table = 'corruptions';          // ชื่อตาราง
    protected $primaryKey = 'corruptions_id';   // primary key
    protected $appends = ['corruptions_date_show_buddhist'];

    public function scopeActive($query)
    {
        return $query->where('corruptions_display', 'A');
    }

    public function getcorruptionDateShowBuddhistAttribute()
    {
        if (!$this->corruptions_date_insert) {
            return null;
        }

        $date = Carbon::parse($this->corruptions_date_insert);
        $thaiYear = $date->year + 543;
        $thaiDate = $date->format('d-m-') . $thaiYear . ' เวลา ' . $date->format('H:i:s');
  
        return $thaiDate;
    }
}
