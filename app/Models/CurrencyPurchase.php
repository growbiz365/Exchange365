<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CurrencyPurchase extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Currency purchase {$eventName}");
    }

    protected $table = 'currency_purchase';
    protected $primaryKey = 'currency_purchase_id';

    protected $fillable = [
        'business_id',
        'currency_id',
        'date_added',
        'currency_amount',
        'unit_cost',
        'voucher_id',
        'voucher_type',
        'user_id',
    ];

    protected $casts = [
        'date_added' => 'date',
        'currency_amount' => 'decimal:4',
        'unit_cost' => 'decimal:6',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'currency_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

