<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $guarded = [];

    // Relasi ke detail spesifik
    public function accidentDetail(): HasOne { return $this->hasOne(ReportAccidentDetail::class); }
    public function unsafeDetail(): HasOne { return $this->hasOne(ReportUnsafeDetail::class); }
    
    // Relasi ke file & log
    public function attachments(): HasMany { return $this->hasMany(ReportAttachment::class); }
    public function logs(): HasMany { return $this->hasMany(ReportLog::class, 'report_id')->latest(); }
    
    // Relasi workflow
    public function plan(): HasOne { return $this->hasOne(ReportPlans::class); }
    public function action(): HasOne { return $this->hasOne(ReportActions::class); }
    
    // Relasi ke PIC (User)
    public function pic(): BelongsTo { return $this->belongsTo(User::class, 'pic_id'); }
}