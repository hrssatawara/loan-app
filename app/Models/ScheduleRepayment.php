<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleRepayment extends Model
{
    use HasFactory;
    public const PENDING = 100;
    public const PAID = 200;

    public const STATUS = [
        self::PENDING => 'PENDING',
        self::PAID => 'PAID',
    ];

    protected $fillable = [
        'loan_id',
        'amount',
        'amount_paid',
        'due_date'
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

    public function scopeUnPaid($query){
        $query->where('status', self::PENDING);
    }
}
