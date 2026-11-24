<?php

namespace App\Models\Package;

use App\Models\Payment\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomPackageOffer extends Model
{
    use HasUuids;

    protected $table = 'custom_package_offers';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'thread_uuid',
        'user_uuid',
        'created_by_uuid',
        'based_package_uuid',
        'based_package_option_uuid',
        'previous_offer_uuid',
        'source_request_uuid',
        'selected_option_uuid',
        'title',
        'description',
        'event_date',
        'guest_count',
        'venue_note',
        'customer_note',
        'admin_note',
        'revision_note',
        'version',
        'status',
        'subtotal_amount',
        'discount_amount',
        'total_amount',
        'deposit_percentage',
        'payment_terms_note',
        'sent_at',
        'expires_at',
        'expiry_reminded_at',
        'response_reminded_at',
        'accepted_at',
        'rejected_at',
        'rejection_reason',
        'rejection_note',
    ];

    protected $casts = [
        'event_date' => 'date',
        'guest_count' => 'integer',
        'version' => 'integer',
        'subtotal_amount' => 'integer',
        'discount_amount' => 'integer',
        'total_amount' => 'integer',
        'deposit_percentage' => 'integer',
        'sent_at' => 'datetime',
        'expires_at' => 'datetime',
        'expiry_reminded_at' => 'datetime',
        'response_reminded_at' => 'datetime',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_uuid', 'uuid');
    }

    public function basedPackage(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'based_package_uuid', 'uuid');
    }

    public function previousOffer(): BelongsTo
    {
        return $this->belongsTo(self::class, 'previous_offer_uuid', 'uuid');
    }

    public function sourceRequest(): BelongsTo
    {
        return $this->belongsTo(CustomPackageOfferRequest::class, 'source_request_uuid', 'uuid');
    }

    public function selectedOption(): BelongsTo
    {
        return $this->belongsTo(CustomPackageOfferOption::class, 'selected_option_uuid', 'uuid');
    }

    public function basedPackageOption(): BelongsTo
    {
        return $this->belongsTo(PackageOption::class, 'based_package_option_uuid', 'uuid');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(self::class, 'thread_uuid', 'thread_uuid')->orderBy('version');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CustomPackageOfferItem::class, 'custom_package_offer_uuid', 'uuid')
            ->orderBy('sort_order');
    }

    public function options(): HasMany
    {
        return $this->hasMany(CustomPackageOfferOption::class, 'custom_package_offer_uuid', 'uuid')
            ->orderBy('sort_order');
    }

    public function workflowEvents(): HasMany
    {
        return $this->hasMany(CustomPackageWorkflowEvent::class, 'offer_uuid', 'uuid')
            ->latest();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'custom_package_offer_uuid', 'uuid');
    }

    public function isLatestVersion(bool $includeDrafts = false): bool
    {
        $query = self::query()
            ->where('thread_uuid', $this->thread_uuid)
            ->where('version', '>', $this->version);

        if (! $includeDrafts) {
            $query->where('status', '!=', 'draft');
        }

        return ! $query->exists();
    }

    public function isExpired(): bool
    {
        return $this->status === 'sent' && $this->expires_at?->isPast();
    }

    public function getDisplayStatusAttribute(): string
    {
        if ($this->isExpired()) {
            return 'expired';
        }

        return $this->status;
    }
}
