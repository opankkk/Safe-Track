<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportUnsafeDetail extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tanggal_pengamatan' => 'date',
    ];

    public function report() { return $this->belongsTo(Report::class); }
}