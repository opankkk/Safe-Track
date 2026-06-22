<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportPlans extends Model
{
    protected $guarded = [];

    public function report() { return $this->belongsTo(Report::class); }
    public function uploader() { return $this->belongsTo(User::class, 'uploaded_by'); }
}