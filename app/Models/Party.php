<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Party extends Model
{
    use HasFactory, LogsActivity;

    public const TYPE_KHATA = 1;

    public const TYPE_OTHER = 2;

    public const TYPE_INCOME = 3;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Party {$eventName}");
    }

    protected $table = 'party';
    protected $primaryKey = 'party_id';

    protected $fillable = [
        'business_id',
        'party_name',
        'contact_no',
        'party_type',
        'status',
        'opening_date',
        'user_id',
    ];

    protected $casts = [
        'opening_date' => 'date',
        'status' => 'integer',
        'party_type' => 'integer',
    ];

    /**
     * Get the business that owns the party.
     */
    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    /**
     * Get the user who created the party.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the opening balances for the party.
     */
    public function openingBalances()
    {
        return $this->hasMany(PartyOpeningBalance::class, 'party_id', 'party_id');
    }

    /**
     * Get the ledger entries for the party.
     */
    public function ledgerEntries()
    {
        return $this->hasMany(PartyLedger::class, 'party_id', 'party_id');
    }

    /**
     * Scope a query to only include active parties.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope a query to filter by business.
     */
    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }

    /**
     * Get party balance for a specific currency.
     */
    public function getBalanceForCurrency($currencyId, $asOfDate = null)
    {
        $query = $this->ledgerEntries()
            ->where('currency_id', $currencyId);

        if ($asOfDate) {
            $query->where('date_added', '<=', $asOfDate);
        }

        $ledger = $query->selectRaw('
            SUM(credit_amount) as total_credit,
            SUM(debit_amount) as total_debit
        ')->first();

        if (!$ledger) {
            return 0;
        }

        return ($ledger->total_credit ?? 0) - ($ledger->total_debit ?? 0);
    }

    /**
     * Get all currency balances for this party.
     */
    public function getCurrencyBalances($asOfDate = null)
    {
        $query = $this->ledgerEntries()
            ->with('currency:currency_id,currency,currency_symbol');

        if ($asOfDate) {
            $query->where('date_added', '<=', $asOfDate);
        }

        return $query->selectRaw('
            currency_id,
            SUM(credit_amount) - SUM(debit_amount) as balance
        ')
        ->groupBy('currency_id')
        ->having('balance', '!=', 0)
        ->get();
    }

    /**
     * Check if party has any transactions.
     */
    public function hasTransactions()
    {
        return $this->ledgerEntries()
            ->where('voucher_type', '!=', 'Opening Balance')
            ->exists();
    }

    /**
     * @return array<int, string>
     */
    public static function partyTypeLabels(): array
    {
        return [
            self::TYPE_KHATA => 'Khata Party',
            self::TYPE_OTHER => 'Other Party',
            self::TYPE_INCOME => 'Income Party',
        ];
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute()
    {
        return $this->status == 1 ? 'Active' : 'Inactive';
    }

    /**
     * Get the party type label.
     */
    public function getPartyTypeLabelAttribute(): string
    {
        return self::partyTypeLabels()[$this->party_type] ?? 'Unknown';
    }

    public function getPartyTypeBadgeClassAttribute(): string
    {
        return match ((int) $this->party_type) {
            self::TYPE_KHATA => 'bg-sky-50 text-sky-700 border-sky-100',
            self::TYPE_OTHER => 'bg-violet-50 text-violet-700 border-violet-100',
            self::TYPE_INCOME => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            default => 'bg-gray-50 text-gray-700 border-gray-100',
        };
    }
}
