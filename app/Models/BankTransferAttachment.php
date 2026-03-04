<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class BankTransferAttachment extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Bank transfer attachment {$eventName}");
    }

    protected $fillable = [
        'bank_transfer_id',
        'file_title',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function bankTransfer()
    {
        return $this->belongsTo(BankTransfer::class, 'bank_transfer_id', 'bank_transfer_id');
    }

    public function getFileUrlAttribute()
    {
        return route('files.bank-transfer-attachments.download', $this);
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}

