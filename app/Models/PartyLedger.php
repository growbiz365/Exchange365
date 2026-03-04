<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PartyLedger extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Party ledger {$eventName}");
    }

    protected $table = 'party_ledger';
    protected $primaryKey = 'party_ledger_id';

    protected $fillable = [
        'party_id',
        'currency_id',
        'voucher_id',
        'voucher_type',
        'credit_amount',
        'debit_amount',
        'date_added',
        'transaction_party',
        'rate',
        'details',
        'user_id',
    ];

    protected $casts = [
        'credit_amount' => 'decimal:2',
        'debit_amount' => 'decimal:2',
        'rate' => 'decimal:4',
        'date_added' => 'date',
    ];

    /**
     * Get the party that owns the ledger entry.
     */
    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id', 'party_id');
    }

    /**
     * Get the currency for this ledger entry.
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'currency_id');
    }

    /**
     * Get the user who created the ledger entry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the running balance attribute.
     */
    public function getRunningBalanceAttribute()
    {
        return $this->credit_amount - $this->debit_amount;
    }
}
