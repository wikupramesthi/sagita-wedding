<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $mode === 'detail' ? 'Detail Calon Pelanggan' : 'Rekap Calon Pelanggan' }}</title>
    <style>
        @page { margin: 12mm 14mm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1f2937; }
        .header { width: 100%; border-bottom: 2px solid #5e55c8; padding-bottom: 10px; margin-bottom: 14px; }
        .brand { font-size: 16px; font-weight: bold; color: #5145bd; }
        .doc-title { font-size: 13px; font-weight: bold; text-align: right; }
        .meta { font-size: 8px; color: #6b7280; text-align: right; }
        .section-title { font-size: 11px; font-weight: bold; color: #111827; margin: 12px 0 6px 0; }
        .stats { width: 100%; border-collapse: separate; border-spacing: 6px 0; margin-bottom: 10px; }
        .stat { border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px; text-align: center; }
        .stat-value { font-size: 14px; font-weight: bold; }
        .stat-label { font-size: 8px; color: #6b7280; }
        .filter-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 10px; font-size: 8px; margin-bottom: 12px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th { background: #f3f4f6; text-align: left; border: 1px solid #e5e7eb; padding: 6px; font-size: 8px; }
        table.data td { border: 1px solid #e5e7eb; padding: 6px; font-size: 8px; vertical-align: top; }
        table.info { width: 100%; border-collapse: collapse; }
        table.info td { border: 1px solid #e5e7eb; padding: 6px 8px; font-size: 9px; vertical-align: top; }
        .label { color: #6b7280; font-size: 8px; display: block; margin-bottom: 2px; }
        .value { font-weight: bold; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 7px; font-weight: bold; }
        .badge-primary { background: #e0e7ff; color: #3730a3; }
        .badge-info { background: #cffafe; color: #155e75; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-secondary { background: #f3f4f6; color: #4b5563; }
        .notes { border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 10px; font-size: 9px; }
        .footer { margin-top: 14px; border-top: 1px solid #e5e7eb; padding-top: 6px; font-size: 7px; color: #9ca3af; text-align: center; }
        .text-muted { color: #6b7280; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td class="brand">{{ $siteName }}</td>
            <td class="doc-title">{{ $mode === 'detail' ? 'Detail Calon Pelanggan' : 'Rekap Calon Pelanggan' }}<br><span class="meta">Dicetak {{ $generatedAt }} oleh {{ $generatedBy }}</span></td>
        </tr>
    </table>

    @php
        $leadBadgeClass = function ($status) {
            return match ($status) {
                'Contacted' => 'badge-info',
                'Interested' => 'badge-primary',
                'Negotiation' => 'badge-warning',
                'Converted' => 'badge-success',
                'Lost' => 'badge-danger',
                default => 'badge-secondary',
            };
        };
        $priorityBadgeClass = function ($priority) {
            return match ($priority) {
                'Tinggi' => 'badge-danger',
                'Sedang' => 'badge-warning',
                default => 'badge-secondary',
            };
        };
    @endphp

    @if ($mode === 'detail')
        <div class="section-title">Profil Calon Pelanggan</div>
        <table class="info">
            <tr>
                <td width="50%"><span class="label">Nama</span><span class="value">{{ $consultation->full_name }}</span></td>
                <td width="50%"><span class="label">WhatsApp</span><span class="value">{{ $consultation->whatsapp }}</span></td>
            </tr>
            <tr>
                <td><span class="label">Email</span><span class="value">{{ $consultation->email ?: '-' }}</span></td>
                <td><span class="label">Informasi dari mana</span><span class="value">{{ $consultation->source_label }}</span></td>
            </tr>
            <tr>
                <td><span class="label">Prioritas</span><span class="badge {{ $priorityBadgeClass($consultation->lead_priority) }}">{{ $consultation->lead_priority }} ({{ $consultation->lead_score }}/10)</span></td>
                <td><span class="label">Status Lead</span><span class="badge {{ $leadBadgeClass($consultation->lead_status) }}">{{ $consultation->lead_status_label }}</span></td>
            </tr>
        </table>

        <div class="section-title">Rencana Pernikahan</div>
        <table class="info">
            <tr>
                <td width="50%"><span class="label">Tanggal Pernikahan</span><span class="value">{{ $consultation->wedding_date?->translatedFormat('d F Y') ?? '-' }}</span></td>
                <td width="50%"><span class="label">Estimasi Tamu</span><span class="value">{{ $consultation->guest_estimate ?: '-' }}</span></td>
            </tr>
            <tr>
                <td><span class="label">Venue</span><span class="value">{{ $consultation->venue ?: '-' }}</span></td>
                <td><span class="label">Paket Diminati</span><span class="value">{{ $consultation->package?->name ?? '-' }}</span></td>
            </tr>
            <tr>
                <td colspan="2"><span class="label">Konsep</span><span class="value">{{ $consultation->wedding_concept ?: '-' }}</span></td>
            </tr>
        </table>

        <div class="section-title">Jadwal & Pipeline</div>
        <table class="info">
            <tr>
                <td width="50%"><span class="label">Tanggal Konsultasi</span><span class="value">{{ $consultation->consultation_date?->translatedFormat('d F Y') ?? '-' }}</span></td>
                <td width="50%"><span class="label">Jam</span><span class="value">{{ $consultation->consultation_time ?: '-' }}</span></td>
            </tr>
            <tr>
                <td><span class="label">Metode</span><span class="value">{{ $consultation->method_label }}</span></td>
                <td><span class="label">Status Konsultasi</span><span class="value">{{ $consultation->status_label }}</span></td>
            </tr>
            <tr>
                <td><span class="label">Penanggung Jawab</span><span class="value">{{ $consultation->handler?->name ?? '-' }}</span></td>
                <td><span class="label">Follow-up</span><span class="value">{{ $consultation->follow_up_label }} · {{ $consultation->follow_up_date_label }}</span></td>
            </tr>
            <tr>
                <td colspan="2"><span class="label">Konversi Booking</span><span class="value">{{ $consultation->converted_to_booking_at?->translatedFormat('d F Y H:i') ?? 'Belum dikonversi' }}</span></td>
            </tr>
        </table>

        <div class="section-title">Catatan Tim</div>
        <div class="notes">{!! $consultation->admin_notes ? nl2br(e($consultation->admin_notes)) : 'Belum ada catatan.' !!}</div>
    @else
        @php
            $filterParts = [];

            if ($filters['q'] !== '') {
                $filterParts[] = 'Pencarian: '.$filters['q'];
            }
            if ($filters['status'] !== '') {
                $filterParts[] = 'Status: '.\App\Models\Consultation::STATUS_LABELS[$filters['status']];
            }
            if ($filters['lead'] !== '') {
                $filterParts[] = 'Lead: '.\App\Models\Consultation::LEAD_STATUS_LABELS[$filters['lead']];
            }
            if ($filters['follow_up'] !== '') {
                $filterParts[] = 'Follow-up: '.\App\Models\Consultation::FOLLOW_UP_LABELS[$filters['follow_up']];
            }
            if ($filters['source'] !== '') {
                $filterParts[] = 'Informasi dari mana: '.(\App\Models\Consultation::SOURCE_LABELS[$filters['source']] ?? $filters['source']);
            }
            if ($filters['start_date'] !== '') {
                $filterParts[] = 'Dari: '.$filters['start_date'];
            }
            if ($filters['end_date'] !== '') {
                $filterParts[] = 'Sampai: '.$filters['end_date'];
            }

            $filterText = $filterParts ? implode(' | ', $filterParts) : 'Semua data';
        @endphp

        <table class="stats">
            <tr>
                <td class="stat"><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">TOTAL LEAD</div></td>
                <td class="stat"><div class="stat-value">{{ $stats['new'] }}</div><div class="stat-label">LEAD BARU</div></td>
                <td class="stat"><div class="stat-value">{{ $stats['hot'] }}</div><div class="stat-label">LEAD PANAS</div></td>
                <td class="stat"><div class="stat-value">{{ $stats['overdue'] }}</div><div class="stat-label">FOLLOW-UP TERLEWAT</div></td>
                <td class="stat"><div class="stat-value">{{ $stats['converted'] }}</div><div class="stat-label">TERKONVERSI</div></td>
                <td class="stat"><div class="stat-value">{{ $stats['conversion_rate'] }}%</div><div class="stat-label">KONVERSI</div></td>
            </tr>
        </table>

        <div class="filter-box"><strong>Filter:</strong> {{ $filterText }}</div>

        <table class="data">
            <thead>
                <tr>
                    <th style="width: 28px;">No</th>
                    <th>Calon Pelanggan</th>
                    <th>Kontak</th>
                    <th>Informasi dari mana</th>
                    <th>Rencana</th>
                    <th>Prioritas</th>
                    <th>Status Lead</th>
                    <th>Follow-up</th>
                    <th>Penanggung Jawab</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($consultations as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->full_name }}</strong></td>
                        <td>{{ $item->whatsapp }}@if ($item->email)<br><span class="text-muted">{{ $item->email }}</span>@endif</td>
                        <td>{{ $item->source_label }}</td>
                        <td>{{ $item->wedding_date?->translatedFormat('d M Y') ?? '-' }}<br><span class="text-muted">{{ $item->guest_estimate ?: 'Tamu -' }}</span></td>
                        <td><span class="badge {{ $priorityBadgeClass($item->lead_priority) }}">{{ $item->lead_priority }}</span></td>
                        <td><span class="badge {{ $leadBadgeClass($item->lead_status) }}">{{ $item->lead_status_label }}</span></td>
                        <td>{{ $item->follow_up_label }}<br><span class="text-muted">{{ $item->follow_up_date_label }}</span></td>
                        <td>{{ $item->handler?->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-muted">Tidak ada data yang cocok dengan filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <div class="footer">Dokumen ini dibuat otomatis dari sistem {{ $siteName }} pada {{ $generatedAt }}.</div>
</body>
</html>
