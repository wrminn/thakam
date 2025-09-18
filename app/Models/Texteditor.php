<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Texteditor extends Model
{
    use HasFactory;

    protected $table = 'texteditor';          // ชื่อตาราง
    protected $primaryKey = 'texteditor_id';   // primary key
    protected $appends = ['texteditor_date_show_buddhist'];


    public function scopeActive($query)
    {
        return $query->where('texteditor_display', 'A');
    }

    public function getTexteditorDateShowBuddhistAttribute()
    {
        if (!$this->texteditor_date_show) {
            return null;
        }

        $date = Carbon::parse($this->texteditor_date_show);
        $thaiYear = $date->year + 543;

        return $date->format('d-m') . '-' . $thaiYear;
    }
}
