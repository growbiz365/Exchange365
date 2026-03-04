<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class BankLedger extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Bank ledger {$eventName}");
    }

    protected $table = 'bank_ledger';
    protected $primaryKey = 'bank_ledger_id';

    protected $fillable = [
        'bank_id',
        'voucher_id',
        'voucher_type',
        'deposit_amount',
        'withdrawal_amount',
        'date_added',
        'details',
        'user_id',
    ];

    protected $casts = [
        'deposit_amount' => 'decimal:2',
        'withdrawal_amount' => 'decimal:2',
        'date_added' => 'date',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'bank_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
