<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_uuid',
        'user_name',
        'user_email',
        'event',
        'auditable_type',
        'auditable_id',
        'auditable_label',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function getModelNameAttribute(): string
    {
        return $this->auditable_type ? class_basename($this->auditable_type) : '-';
    }

    public function getEventLabelAttribute(): string
    {
        return match ($this->event) {
            'created' => 'Dibuat',
            'updated' => 'Diperbarui',
            'deleted' => 'Dihapus',
            'restored' => 'Dipulihkan',
            'whatsapp_opened' => 'WhatsApp Dibuka',
            'approved' => 'Pesanan Disetujui',
            'rejected' => 'Pesanan Ditolak',
            'proof_uploaded' => 'Bukti Diunggah',
            'assigned' => 'Pesanan Diambil Alih',
            'approval_reverted' => 'Persetujuan Dibatalkan',
            'rejection_reverted' => 'Penolakan Dibatalkan',
            'sla_reminded' => 'Pengingat SLA Dikirim',
            'order_expired' => 'Pesanan Kedaluwarsa',
            'balance_reminded' => 'Pengingat Pembayaran Dikirim',
            'checklist_reminded' => 'Pengingat Checklist Dikirim',
            'checklist_escalated' => 'Eskalasi Checklist Dikirim',
            'vendor_assigned' => 'Vendor Ditugaskan',
            'vendor_status_changed' => 'Status Vendor Diperbarui',
            'vendor_unassigned' => 'Vendor Dilepaskan',
            'change_request_submitted' => 'Perubahan Pesanan Diajukan',
            'change_request_rejected' => 'Perubahan Pesanan Ditolak',
            'event_date_rescheduled' => 'Tanggal Acara Diubah',
            'addon_change_approved' => 'Layanan Tambahan Disetujui',
            'cancellation_requested' => 'Pembatalan Diajukan',
            'cancellation_approved' => 'Pesanan Dibatalkan',
            'cancellation_rejected' => 'Pembatalan Ditolak',
            'refund_recorded' => 'Refund Ditransfer',
            default => 'Lainnya',
        };
    }

    public function getEventBadgeAttribute(): string
    {
        return match ($this->event) {
            'created' => 'bg-success-subtle text-success',
            'updated' => 'bg-warning-subtle text-warning',
            'deleted' => 'bg-danger-subtle text-danger',
            'restored' => 'bg-info-subtle text-info',
            'approved' => 'bg-success-subtle text-success',
            'rejected' => 'bg-danger-subtle text-danger',
            'proof_uploaded' => 'bg-info-subtle text-info',
            'whatsapp_opened' => 'bg-success-subtle text-success',
            'assigned' => 'bg-primary-subtle text-primary',
            'approval_reverted' => 'bg-warning-subtle text-warning',
            'rejection_reverted' => 'bg-info-subtle text-info',
            'sla_reminded' => 'bg-warning-subtle text-warning',
            'order_expired' => 'bg-secondary-subtle text-secondary',
            'balance_reminded' => 'bg-info-subtle text-info',
            'checklist_reminded' => 'bg-primary-subtle text-primary',
            'checklist_escalated' => 'bg-warning-subtle text-warning',
            'vendor_assigned' => 'bg-primary-subtle text-primary',
            'vendor_status_changed' => 'bg-info-subtle text-info',
            'vendor_unassigned' => 'bg-warning-subtle text-warning',
            'change_request_submitted' => 'bg-info-subtle text-info',
            'change_request_rejected' => 'bg-danger-subtle text-danger',
            'event_date_rescheduled' => 'bg-primary-subtle text-primary',
            'addon_change_approved' => 'bg-success-subtle text-success',
            'cancellation_requested' => 'bg-warning-subtle text-warning',
            'cancellation_approved' => 'bg-secondary-subtle text-secondary',
            'cancellation_rejected' => 'bg-danger-subtle text-danger',
            'refund_recorded' => 'bg-success-subtle text-success',
            default => 'bg-secondary-subtle text-secondary',
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function changes(): array
    {
        $old = $this->old_values ?? [];
        $new = $this->new_values ?? [];
        $keys = array_unique(array_merge(array_keys($old), array_keys($new)));

        $result = [];
        foreach ($keys as $key) {
            $result[$key] = [
                'old' => $old[$key] ?? null,
                'new' => $new[$key] ?? null,
            ];
        }

        return $result;
    }
}
