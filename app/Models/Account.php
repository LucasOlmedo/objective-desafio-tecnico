<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'account_number',
        'balance',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
