<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class BankType extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Bank type {$eventName}");
    }

    protected $table = 'bank_type';
    protected $primaryKey = 'bank_type_id';

    protected $fillable = ['bank_type'];

    public function banks()
    {
        return $this->hasMany(Bank::class, 'bank_type_id', 'bank_type_id');
    }
}
