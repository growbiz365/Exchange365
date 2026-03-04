<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PartyTransferAttachment extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Party transfer attachment {$eventName}");
    }

    protected $fillable = [
        'party_transfer_id',
        'file_title',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    /**
     * Relationships
     */
    public function partyTransfer()
    {
        return $this->belongsTo(PartyTransfer::class, 'party_transfer_id', 'party_transfer_id');
    }

    /**
     * Get the full URL for the file (served via controller so it works without storage link)
     */
    public function getFileUrlAttribute()
    {
        return route('files.party-transfer-attachments.download', $this);
    }

    /**
     * Get human-readable file size
     */
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
