<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class BankTransfer extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Bank transfer {$eventName}");
    }

    protected $table = 'bank_transfer';
    protected $primaryKey = 'bank_transfer_id';

    protected $fillable = [
        'business_id',
        'date_added',
        'from_account_id',
        'to_account_id',
        'amount',
        'details',
        'user_id',
    ];

    protected $casts = [
        'date_added' => 'date',
        'amount' => 'decimal:2',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function fromBank()
    {
        return $this->belongsTo(Bank::class, 'from_account_id', 'bank_id');
    }

    public function toBank()
    {
        return $this->belongsTo(Bank::class, 'to_account_id', 'bank_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(BankTransferAttachment::class, 'bank_transfer_id', 'bank_transfer_id');
    }

    public function ledgerEntries()
    {
        return $this->hasMany(BankLedger::class, 'voucher_id', 'bank_transfer_id')
            ->where('voucher_type', 'Bank Transfer');
    }

    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }
}

