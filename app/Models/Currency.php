<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $table = 'currency';
    protected $primaryKey = 'currency_id';

    protected $fillable = [
        'currency',
        'currency_symbol',
        'status',
        'default_currency',
    ];

    protected $casts = [
        'status' => 'integer',
        'default_currency' => 'integer',
    ];

    /**
     * Scope to get only active currencies.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Get the default currency.
     */
    public static function getDefault()
    {
        return static::where('default_currency', 1)->first();
    }
}
