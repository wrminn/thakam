<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';          // ชื่อตาราง
    protected $primaryKey = 'contacts_id';   // primary key
    protected $appends = ['contacts_date_show_buddhist'];

    public function scopeActive($query)
    {
        return $query->where('contacts_display', 'A');
    }

    public function getContactDateShowBuddhistAttribute()
    {
        if (!$this->contacts_date_insert) {
            return null;
        }

        $date = Carbon::parse($this->contacts_date_insert);
        $thaiYear = $date->year + 543;
        $thaiDate = $date->format('d-m-') . $thaiYear . ' เวลา ' . $date->format('H:i:s');
  
        return $thaiDate;
    }
}
