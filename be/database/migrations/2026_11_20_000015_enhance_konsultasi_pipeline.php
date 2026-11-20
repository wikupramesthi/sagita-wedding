<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $indexes = [
        'cons_created_at_idx' => ['created_at'],
        'cons_status_created_idx' => ['consultation_status', 'created_at'],
        'cons_lead_created_idx' => ['lead_status', 'created_at'],
        'cons_source_created_idx' => ['source', 'created_at'],
        'cons_followup_lead_idx' => ['follow_up_at', 'lead_status'],
        'cons_handler_lead_idx' => ['handled_by', 'lead_status'],
        'cons_package_idx' => ['package_id'],
        'cons_converted_idx' => ['converted_to_booking_at'],
        'cons_email_norm_idx' => ['email_normalized'],
        'cons_wa_e164_idx' => ['whatsapp_e164'],
        'cons_merged_into_idx' => ['merged_into_uuid'],
    ];

    private array $routes = [
        'konsultasi.bulk' => 'Aksi massal konsultasi',
        'konsultasi.export' => 'Ekspor data konsultasi',
        'konsultasi.kanban' => 'Papan kanban konsultasi',
        'konsultasi.calendar' => 'Kalender jadwal konsultasi',
        'konsultasi.duplicates' => 'Deteksi duplikat konsultasi',
        'konsultasi.merge' => 'Gabungkan data konsultasi',
        'konsultasi.remind' => 'Kirim pengingat konsultasi',
    ];

    public function up(): void
    {
        if (Schema::hasTable('consultations')) {
            if (! Schema::hasColumn('consultations', 'email_normalized')) {
                Schema::table('consultations', function (Blueprint $table) {
                    $table->string('email_normalized', 255)->nullable()->after('email');
                });
            }

            if (! Schema::hasColumn('consultations', 'whatsapp_e164')) {
                Schema::table('consultations', function (Blueprint $table) {
                    $table->string('whatsapp_e164', 20)->nullable()->after('whatsapp');
                });
            }

            if (! Schema::hasColumn('consultations', 'reminder_sent_at')) {
                Schema::table('consultations', function (Blueprint $table) {
                    $table->timestamp('reminder_sent_at')->nullable()->after('follow_up_at');
                });
            }

            if (! Schema::hasColumn('consultations', 'merged_into_uuid')) {
                Schema::table('consultations', function (Blueprint $table) {
                    $table->string('merged_into_uuid', 36)->nullable()->after('source');
                });
            }

            if (! Schema::hasColumn('consultations', 'merged_at')) {
                Schema::table('consultations', function (Blueprint $table) {
                    $table->timestamp('merged_at')->nullable()->after('merged_into_uuid');
                });
            }

            if (! Schema::hasColumn('consultations', 'merged_by')) {
                Schema::table('consultations', function (Blueprint $table) {
                    $table->string('merged_by', 36)->nullable()->after('merged_at');
                });
            }

            DB::table('consultations')
                ->whereNull('email_normalized')
                ->whereNotNull('email')
                ->update(['email_normalized' => DB::raw('LOWER(TRIM(email))')]);

            DB::table('consultations')
                ->select('uuid', 'whatsapp')
                ->whereNull('whatsapp_e164')
                ->whereNotNull('whatsapp')
                ->orderBy('created_at')
                ->chunk(500, function ($rows) {
                    foreach ($rows as $row) {
                        $number = preg_replace('/\D+/', '', (string) $row->whatsapp);

                        if ($number === '') {
                            continue;
                        }

                        if (strpos($number, '0') === 0) {
                            $number = '62'.substr($number, 1);
                        } elseif (strpos($number, '8') === 0) {
                            $number = '62'.$number;
                        }

                        DB::table('consultations')
                            ->where('uuid', $row->uuid)
                            ->update(['whatsapp_e164' => $number]);
                    }
                });

            foreach ($this->indexes as $name => $columns) {
                $this->addIndexIfMissing('consultations', $columns, $name);
            }
        }

        foreach ($this->routes as $name => $description) {
            DB::table('routes')->updateOrInsert(
                ['route' => $name],
                [
                    'permission_name' => 'konsultasi.index',
                    'status' => 1,
                    'description' => $description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('consultations')) {
            foreach (array_keys($this->indexes) as $name) {
                $this->dropIndexIfMissing('consultations', $name);
            }

            $columns = array_values(array_filter([
                'email_normalized',
                'whatsapp_e164',
                'reminder_sent_at',
                'merged_into_uuid',
                'merged_at',
                'merged_by',
            ], fn ($column) => Schema::hasColumn('consultations', $column)));

            if (! empty($columns)) {
                Schema::table('consultations', function (Blueprint $table) use ($columns) {
                    $table->dropColumn($columns);
                });
            }
        }

        DB::table('routes')->whereIn('route', array_keys($this->routes))->delete();
    }

    private function addIndexIfMissing(string $table, array $columns, string $name): void
    {
        if ($this->indexExists($table, $name)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($columns, $name) {
            $blueprint->index($columns, $name);
        });
    }

    private function dropIndexIfMissing(string $table, string $name): void
    {
        if (! $this->indexExists($table, $name)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($name) {
            $blueprint->dropIndex($name);
        });
    }

    private function indexExists(string $table, string $name): bool
    {
        return DB::table('INFORMATION_SCHEMA.STATISTICS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('INDEX_NAME', $name)
            ->exists();
    }
};
