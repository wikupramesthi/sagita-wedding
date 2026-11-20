<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekap Konsultasi</title>
</head>

<body>
    <table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 11px;">
        <thead>
            <tr style="background-color: #f3f4f6; font-weight: bold; text-align: center;">
                <th>No</th>
                <th>Nama</th>
                <th>WhatsApp</th>
                <th>Email</th>
                <th>Sumber</th>
                <th>Package</th>
                <th>Tanggal Konsultasi</th>
                <th>Status Konsultasi</th>
                <th>Status Lead</th>
                <th>Prioritas</th>
                <th>Penanggung Jawab</th>
                <th>Tindak Lanjut</th>
                <th>Catatan</th>
                <th>Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($consultations as $index => $consultation)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ \App\Exports\ConsultationExport::escape($consultation->full_name) }}</td>
                    <td>{{ \App\Exports\ConsultationExport::escape($consultation->whatsapp ?: '-') }}</td>
                    <td>{{ \App\Exports\ConsultationExport::escape($consultation->email ?: '-') }}</td>
                    <td>{{ \App\Exports\ConsultationExport::escape($consultation->source_label) }}</td>
                    <td>{{ \App\Exports\ConsultationExport::escape($consultation->package?->name ?? '-') }}</td>
                    <td>{{ $consultation->consultation_date?->translatedFormat('d M Y') ?? '-' }}</td>
                    <td>{{ \App\Exports\ConsultationExport::escape($consultation->status_label) }}</td>
                    <td>{{ \App\Exports\ConsultationExport::escape($consultation->lead_status_label) }}</td>
                    <td>{{ \App\Exports\ConsultationExport::escape($consultation->lead_priority) }}</td>
                    <td>{{ \App\Exports\ConsultationExport::escape($consultation->handler?->name ?? '-') }}</td>
                    <td>{{ \App\Exports\ConsultationExport::escape($consultation->follow_up_date_label) }}</td>
                    <td style="white-space: normal;">{{ \App\Exports\ConsultationExport::escape($consultation->admin_notes ?: '-') }}</td>
                    <td>{{ $consultation->created_at?->translatedFormat('d M Y H:i') ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" style="text-align: center;">Tidak ada data konsultasi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
