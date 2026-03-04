<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MoneyExchangeAttachment extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Money exchange attachment {$eventName}");
    }

    protected $fillable = [
        'money_exchange_id',
        'file_title',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function moneyExchange()
    {
        return $this->belongsTo(MoneyExchange::class, 'money_exchange_id', 'money_exchange_id');
    }

    public function getFileUrlAttribute()
    {
        return route('files.money-exchange-attachments.download', $this);
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}

