<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            $prefix = match ($model->type) {
                'accident' => 'ACC',
                'unsafe_action' => 'UA',
                'unsafe_condition' => 'UC',
                default => 'REP'
            };

            $month = date('m');
            $year = date('Y');

            $pattern = sprintf('%s-%s%s-%%', $prefix, $year, $month);
            
            $lastReport = static::where('report_number', 'like', $pattern)
                ->orderBy('id', 'desc')
                ->first();

            $sequence = 1;
            if ($lastReport) {
                $parts = explode('-', $lastReport->report_number);
                if (count($parts) === 3) {
                    $lastSequence = (int) end($parts);
                    $sequence = $lastSequence + 1;
                }
            }

            $model->report_number = sprintf('%s-%s%s-%03d', $prefix, $year, $month, $sequence);
        });
    }

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