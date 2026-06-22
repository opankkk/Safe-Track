<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportAccidentDetail extends Model
{
    protected $guarded = [];

    protected $casts = [
        'kondisi_korban' => 'array',
        'tanggal' => 'date',
    ];

    public function report() { return $this->belongsTo(Report::class); }
}