<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepaymentScheduler extends Model
{
    use HasFactory;
    private const STATUS = [1 => 'PENDING', 2 => "PAID", 3 => "OTHER"];

    protected $fillable = ['user_id', 'loan_account_id', 'nurration', 'term', 'amount', 'due_date'];


    /**
     * Get the status in string value
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => RepaymentScheduler::STATUS[$value],
        );
    }
}
