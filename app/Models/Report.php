<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $guarded = [];

    const SUB_PENDING_HSE             = 'pending_hse';
    const SUB_WAITING_PIC             = 'waiting_pic';
    const SUB_PLAN_VERIFICATION       = 'plan_verification';
    const SUB_PLAN_REJECTED_MANAGER   = 'plan_rejected_manager';
    const SUB_PLAN_APPROVED_MANAGER   = 'plan_approved_manager';
    const SUB_PIC_WORKING             = 'pic_working';
    const SUB_REPORT_PENDING_HSE      = 'report_pending_hse';
    const SUB_REPORT_VERIFICATION_MANAGER = 'report_verification_manager';
    const SUB_REPORT_VERIFICATION_HSE     = 'report_verification_hse';
    const SUB_REPORT_REJECTED_MANAGER     = 'report_rejected_manager';
    const SUB_REPORT_REJECTED_HSE         = 'report_rejected_hse';
    const SUB_CLOSED                      = 'closed';

    public static function subStatusLabel(string $sub): string
    {
        return match ($sub) {
            self::SUB_PENDING_HSE             => 'Pending : Review HSE',
            self::SUB_WAITING_PIC             => 'Dalam Proses PIC',
            self::SUB_PLAN_VERIFICATION       => 'Pending : Verifikasi Plan',
            self::SUB_PLAN_REJECTED_MANAGER   => 'Ditolak : Plan',
            self::SUB_PLAN_APPROVED_MANAGER   => 'Dalam Proses PIC',
            self::SUB_PIC_WORKING             => 'Dalam Pengerjaan PIC',
            self::SUB_REPORT_VERIFICATION_MANAGER => 'Pending : Verifikasi Hasil',
            self::SUB_REPORT_VERIFICATION_HSE     => 'Verifikasi Hasil HSE',
            self::SUB_REPORT_REJECTED_MANAGER => 'Report: Ditolak Manager',
            self::SUB_REPORT_REJECTED_HSE     => 'Report: Ditolak HSE',
            self::SUB_REPORT_PENDING_HSE      => 'Verifikasi Hasil Manager',
            self::SUB_CLOSED                  => 'Selesai',
            default                           => ucfirst(str_replace('_', ' ', $sub)),
        };
    }

    public static function subStatusBadgeClass(string $sub): string
    {
        return match ($sub) {
            self::SUB_PENDING_HSE             => 'badge-warning',
            self::SUB_WAITING_PIC             => 'badge-info',
            self::SUB_PLAN_VERIFICATION       => 'badge-primary',
            self::SUB_PLAN_REJECTED_MANAGER   => 'badge-danger',
            self::SUB_PLAN_APPROVED_MANAGER   => 'badge-success',
            self::SUB_PIC_WORKING             => 'badge-info',
            self::SUB_REPORT_VERIFICATION_MANAGER => 'badge-primary',
            self::SUB_REPORT_VERIFICATION_HSE     => 'badge-info',
            self::SUB_REPORT_REJECTED_MANAGER => 'badge-danger',
            self::SUB_REPORT_REJECTED_HSE     => 'badge-danger',
            self::SUB_REPORT_PENDING_HSE      => 'badge-primary',
            self::SUB_CLOSED                  => 'badge-dark',
            default                           => 'badge-secondary',
        };
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $prefix = match ($model->type) {
                'accident'         => 'ACC',
                'unsafe_action'    => 'UA',
                'unsafe_condition' => 'UC',
                default            => 'REP',
            };

            $month = date('m');
            $year  = date('Y');
            $pattern = sprintf('%s-%s%s-%%', $prefix, $year, $month);

            $lastReport = static::where('report_number', 'like', $pattern)
                ->orderBy('id', 'desc')
                ->first();

            $sequence = 1;
            if ($lastReport) {
                $parts = explode('-', $lastReport->report_number);
                if (count($parts) === 3) {
                    $sequence = ((int) end($parts)) + 1;
                }
            }

            $model->report_number = sprintf('%s-%s%s-%03d', $prefix, $year, $month, $sequence);

            // Default sub_status
            if (empty($model->sub_status)) {
                $model->sub_status = self::SUB_PENDING_HSE;
            }
        });
    }

    // Relasi
    public function accidentDetail(): HasOne  { return $this->hasOne(ReportAccidentDetail::class); }
    public function unsafeDetail(): HasOne    { return $this->hasOne(ReportUnsafeDetail::class); }
    public function attachments(): HasMany    { return $this->hasMany(ReportAttachment::class); }
    public function logs(): HasMany          { return $this->hasMany(ReportLog::class, 'report_id')->latest(); }
    public function plan(): HasOne           { return $this->hasOne(ReportPlans::class)->latest(); }
    public function action(): HasOne         { return $this->hasOne(ReportActions::class)->latest(); }
    public function pic(): BelongsTo         { return $this->belongsTo(User::class, 'pic_id'); }

    public function hasNote(): bool
    {
        return !empty($this->hse_note) || 
               !empty($this->plan?->manager_note) || 
               !empty($this->action?->manager_note) || 
               !empty($this->action?->hse_note);
    }

    public function latestNote(): ?string
    {
        return $this->hse_note
            ?? $this->action?->hse_note
            ?? $this->action?->manager_note
            ?? $this->plan?->manager_note;
    }
}