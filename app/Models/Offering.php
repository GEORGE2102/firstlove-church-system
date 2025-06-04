<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offering extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fellowship_id',
        'amount',
        'offering_date',
        'payment_method',
        'transaction_reference',
        'notes',
        'submitted_by',
        'confirmed_by',
        'confirmed_at',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'offering_date' => 'date',
        'confirmed_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Offering status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_REJECTED = 'rejected';

    /**
     * Payment method constants
     */
    const METHOD_CASH = 'cash';
    const METHOD_MOBILE_MONEY = 'mobile_money';
    const METHOD_BANK_TRANSFER = 'bank_transfer';
    const METHOD_CHEQUE = 'cheque';

    /**
     * Get all available statuses
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    /**
     * Get all available payment methods
     */
    public static function getPaymentMethods()
    {
        return [
            self::METHOD_CASH => 'Cash',
            self::METHOD_MOBILE_MONEY => 'Mobile Money',
            self::METHOD_BANK_TRANSFER => 'Bank Transfer',
            self::METHOD_CHEQUE => 'Cheque',
        ];
    }

    /**
     * Get the fellowship this offering belongs to
     */
    public function fellowship()
    {
        return $this->belongsTo(Fellowship::class);
    }

    /**
     * Get the user who submitted this offering
     */
    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Get the user who confirmed this offering
     */
    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    /**
     * Mark offering as confirmed
     */
    public function confirm(User $user)
    {
        $this->update([
            'status' => self::STATUS_CONFIRMED,
            'confirmed_by' => $user->id,
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Mark offering as rejected
     */
    public function reject(User $user)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'confirmed_by' => $user->id,
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Check if offering is pending
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if offering is confirmed
     */
    public function isConfirmed()
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    /**
     * Check if offering is rejected
     */
    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Scope to filter by fellowship
     */
    public function scopeByFellowship($query, $fellowshipId)
    {
        return $query->where('fellowship_id', $fellowshipId);
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get pending offerings
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope to get confirmed offerings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    /**
     * Scope to get rejected offerings
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('offering_date', [$startDate, $endDate]);
    }

    /**
     * Scope to filter by current month
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('offering_date', now()->month)
                    ->whereYear('offering_date', now()->year);
    }

    /**
     * Scope to get recent offerings
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('offering_date', 'desc')->limit($limit);
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute()
    {
        $symbol = config('app.church.currency_symbol', 'K');
        return $symbol . ' ' . number_format($this->amount, 2);
    }

    /**
     * Get formatted offering date
     */
    public function getFormattedDateAttribute()
    {
        return $this->offering_date->format('l, F j, Y');
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'badge-warning',
            self::STATUS_CONFIRMED => 'badge-success',
            self::STATUS_REJECTED => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    /**
     * Get payment method display name
     */
    public function getPaymentMethodDisplayAttribute()
    {
        return self::getPaymentMethods()[$this->payment_method] ?? ucfirst($this->payment_method);
    }

    /**
     * Get days since submission
     */
    public function getDaysSinceSubmissionAttribute()
    {
        return $this->created_at->diffInDays(now());
    }
} 