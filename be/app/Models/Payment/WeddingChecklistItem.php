<?php

namespace App\Models\Payment;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingChecklistItem extends Model
{
    use HasUuids;

    protected $table = 'wedding_checklist_items';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'order_uuid',
        'title',
        'description',
        'due_at',
        'status',
        'completed_at',
        'completed_by',
        'reminder_sent_at',
        'escalation_sent_at',
    ];

    protected $casts = [
        'due_at' => 'date',
        'completed_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'escalation_sent_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_uuid', 'uuid');
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by', 'uuid');
    }
}
