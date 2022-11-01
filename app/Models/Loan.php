<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    public const PENDING = 100;
    public const APPROVED = 200;
    public const PAID = 300;

    public const STATUS = [
        self::PENDING => 'PENDING',
        self::APPROVED => 'APPROVED',
        self::PAID => 'PAID',
    ];

    protected $fillable = [
        'user_id',
        'amount',
        'tenure',
    ];

    protected $attributes = [
        'status' => self::PENDING,
    ];

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => self::STATUS[$value],
        );
    }

    /**
     * Get the user that owns the loan.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scheduleRepayments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ScheduleRepayment::class);
    }


}
