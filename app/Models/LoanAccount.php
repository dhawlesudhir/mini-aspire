<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanAccount extends Model
{
    use HasFactory;

    private const STATUS = [1 => 'PENDING', 2 => "APPROVED", 3 => "PAID"];

    protected $fillable = [
        'user_id',
        'amount',
        'terms',
        'purpose',
        'bal_amount',
        'status'
    ];


    protected $hidden = [
        // "user_id",
        // "bal_amount",
        "updated_at",
        // "created_at",
        // "id",
        // "approved_by",
        // "approved_on",
    ];


    /**
     * Get the status in string value
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => LoanAccount::STATUS[$value],
        );
    }
}
