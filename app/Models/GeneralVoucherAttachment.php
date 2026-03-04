<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class GeneralVoucherAttachment extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "General voucher attachment {$eventName}");
    }

    protected $table = 'general_voucher_attachments';

    protected $fillable = [
        'general_voucher_id',
        'file_title',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function generalVoucher()
    {
        return $this->belongsTo(GeneralVoucher::class, 'general_voucher_id', 'general_voucher_id');
    }

    public function getFileUrlAttribute()
    {
        return route('files.general-voucher-attachments.download', $this);
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = (int) $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes > 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
