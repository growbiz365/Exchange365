<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PartyOpeningBalance extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Party opening balance {$eventName}");
    }

    protected $table = 'party_opening_balances';
    protected $primaryKey = 'party_opening_balance_id';

    protected $fillable = [
        'party_id',
        'currency_id',
        'entry_type',
        'opening_balance',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'entry_type' => 'integer',
    ];

    /**
     * Get the party that owns the opening balance.
     */
    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id', 'party_id');
    }

    /**
     * Get the currency for this opening balance.
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'currency_id');
    }

    /**
     * Get the entry type label.
     */
    public function getEntryTypeLabelAttribute()
    {
        return $this->entry_type == 1 ? 'Credit (We owe)' : 'Debit (They owe)';
    }
}
