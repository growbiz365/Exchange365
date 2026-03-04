<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Purchase extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Purchase {$eventName}");
    }

    protected $table = 'purchase';
    protected $primaryKey = 'purchase_id';

    protected $fillable = [
        'business_id',
        'date_added',
        'bank_id',
        'party_id',
        'party_currency_id',
        'transaction_operation',
        'credit_amount',
        'rate',
        'debit_amount',
        'details',
        'user_id',
    ];

    protected $casts = [
        'date_added' => 'date',
        'credit_amount' => 'decimal:2',
        'debit_amount' => 'decimal:2',
        'rate' => 'decimal:4',
        'transaction_operation' => 'integer',
    ];

    public const VOUCHER_TYPE = 'Purchase';

    /** Transaction operation: Divide — Debit = Credit ÷ Rate */
    public const TRANSACTION_DIVIDE = 1;
    /** Transaction operation: Multiply — Debit = Credit × Rate */
    public const TRANSACTION_MULTIPLY = 2;

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

    public function partyCurrency()
    {
        return $this->belongsTo(Currency::class, 'party_currency_id', 'currency_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }

    public function getRouteKeyName(): string
    {
        return 'purchase_id';
    }

    /** Human-readable transaction operation label (Divide / Multiply). */
    public function getTransactionOperationLabelAttribute(): string
    {
        return $this->transaction_operation === self::TRANSACTION_MULTIPLY ? 'Multiply' : 'Divide';
    }
}
