<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class GeneralVoucher extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "General voucher {$eventName}");
    }

    protected $table = 'general_voucher';
    protected $primaryKey = 'general_voucher_id';

    protected $fillable = [
        'business_id',
        'date_added',
        'bank_id',
        'party_id',
        'entry_type',
        'amount',
        'rate',
        'details',
        'user_id',
    ];

    protected $casts = [
        'date_added' => 'date',
        'amount' => 'decimal:2',
        'rate' => 'decimal:4',
        'entry_type' => 'integer',
    ];

    public const ENTRY_TYPE_CREDIT = 1;
    public const ENTRY_TYPE_DEBIT = 2;

    public const VOUCHER_TYPE = 'General Voucher';

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'bank_id');
    }

    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id', 'party_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(GeneralVoucherAttachment::class, 'general_voucher_id', 'general_voucher_id');
    }

    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }

    public function getEntryTypeLabelAttribute(): string
    {
        return $this->entry_type === self::ENTRY_TYPE_CREDIT ? 'Credit' : 'Debit';
    }

    public function getRouteKeyName(): string
    {
        return 'general_voucher_id';
    }
}
