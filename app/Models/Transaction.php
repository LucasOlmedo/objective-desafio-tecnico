<?php

namespace App\Models;

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

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
