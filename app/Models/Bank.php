<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Bank extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Bank {$eventName}");
    }

    protected $table = 'banks';
    protected $primaryKey = 'bank_id';

    protected $fillable = [
        'business_id',
        'bank_name',
        'currency_id',
        'account_number',
        'bank_type_id',
        'opening_balance',
        'status',
        'user_id',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'status' => 'integer',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'currency_id');
    }

    public function bankType()
    {
        return $this->belongsTo(BankType::class, 'bank_type_id', 'bank_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ledgerEntries()
    {
        return $this->hasMany(BankLedger::class, 'bank_id', 'bank_id');
    }

    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
