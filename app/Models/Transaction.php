<?php

namespace App\Models;

use App\Enums\TransactionTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'account_id',
        'amount',
        'total_amount',
        'fee',
        'type',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('created_at', function ($query) {
            $query->orderBy('created_at', 'desc');
        });
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function getFormattedTypeAttribute()
    {
        return TransactionTypeEnum::getDescription($this->type);
    }
}
