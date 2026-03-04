<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Asset extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Asset {$eventName}");
    }

    public const VOUCHER_TYPE = 'Assets';
    public const STATUS_ACTIVE = 1;
    public const STATUS_SOLD = 2;

    protected $table = 'assets';
    protected $primaryKey = 'asset_id';

    protected $fillable = [
        'business_id',
        'asset_category_id',
        'date_added',
        'purchase_transaction_type',
        'asset_name',
        'cost_amount',
        'purchase_bank_id',
        'purchase_party_id',
        'purchase_details',
        'asset_status',
        'sale_date',
        'sale_transaction_type',
        'sale_amount',
        'sale_bank_id',
        'sale_party_id',
        'sale_details',
        'user_id',
    ];

    protected $casts = [
        'date_added' => 'date',
        'sale_date' => 'date',
        'cost_amount' => 'decimal:2',
        'sale_amount' => 'decimal:2',
        'purchase_transaction_type' => 'integer',
        'sale_transaction_type' => 'integer',
        'asset_status' => 'integer',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id', 'asset_category_id');
    }

    public function purchaseBank()
    {
        return $this->belongsTo(Bank::class, 'purchase_bank_id', 'bank_id');
    }

    public function saleBank()
    {
        return $this->belongsTo(Bank::class, 'sale_bank_id', 'bank_id');
    }

    public function purchaseParty()
    {
        return $this->belongsTo(Party::class, 'purchase_party_id', 'party_id');
    }

    public function saleParty()
    {
        return $this->belongsTo(Party::class, 'sale_party_id', 'party_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }

    public function scopeActive($query)
    {
        return $query->where('asset_status', self::STATUS_ACTIVE);
    }
}

