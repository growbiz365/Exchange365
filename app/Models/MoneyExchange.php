<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MoneyExchange extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Money exchange {$eventName}");
    }

    protected $table = 'money_exchange';
    protected $primaryKey = 'money_exchange_id';

    protected $fillable = [
        'business_id',
        'date_added',
        'from_account_id',
        'to_account_id',
        'transaction_operation',
        'debit_amount',
        'credit_amount',
        'rate',
        'details',
        'user_id',
    ];

    protected $casts = [
        'date_added' => 'date',
        'debit_amount' => 'decimal:2',
        'credit_amount' => 'decimal:2',
        'rate' => 'decimal:4',
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
        return $this->hasMany(MoneyExchangeAttachment::class, 'money_exchange_id', 'money_exchange_id');
    }

    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }
}

