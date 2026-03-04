<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PartyTransfer extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Party transfer {$eventName}");
    }

    protected $table = 'party_transfer';
    protected $primaryKey = 'party_transfer_id';

    protected $fillable = [
        'business_id',
        'date_added',
        'rate',
        'transaction_operation',
        'credit_party',
        'credit_currency_id',
        'credit_amount',
        'debit_party',
        'debit_currency_id',
        'debit_amount',
        'details',
        'user_id',
    ];

    protected $casts = [
        'date_added' => 'date',
        'rate' => 'decimal:4',
        'credit_amount' => 'decimal:2',
        'debit_amount' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function creditParty()
    {
        return $this->belongsTo(Party::class, 'credit_party', 'party_id');
    }

    public function debitParty()
    {
        return $this->belongsTo(Party::class, 'debit_party', 'party_id');
    }

    public function creditCurrency()
    {
        return $this->belongsTo(Currency::class, 'credit_currency_id', 'currency_id');
    }

    public function debitCurrency()
    {
        return $this->belongsTo(Currency::class, 'debit_currency_id', 'currency_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(PartyTransferAttachment::class, 'party_transfer_id', 'party_transfer_id');
    }

    public function ledgerEntries()
    {
        return $this->hasMany(PartyLedger::class, 'voucher_id', 'party_transfer_id')
            ->where('voucher_type', 'Party Transfer');
    }

    /**
     * Scopes
     */
    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }

    public function scopeDateRange($query, $dateFrom, $dateTo)
    {
        if ($dateFrom) {
            $query->where('date_added', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('date_added', '<=', $dateTo);
        }
        return $query;
    }
}
