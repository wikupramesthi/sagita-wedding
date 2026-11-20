<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $dedupeColumns = [
        'email_normalized' => 'email_dedupe_key',
        'whatsapp_e164' => 'whatsapp_dedupe_key',
    ];

    private array $uniqueIndexes = [
        'cons_email_dedupe_uniq' => 'email_dedupe_key',
        'cons_wa_dedupe_uniq' => 'whatsapp_dedupe_key',
    ];

    public function up(): void
    {
        if (Schema::hasTable('consultations')) {
            if (! Schema::hasColumn('consultations', 'deleted_at')) {
                Schema::table('consultations', fn (Blueprint $table) => $table->softDeletes());
            }

            if (! Schema::hasColumn('consultations', 'sort_order')) {
                Schema::table('consultations', function (Blueprint $table) {
                    $table->unsignedInteger('sort_order')->default(0)->after('lead_status');
                });
            }

            foreach ($this->dedupeColumns as $key) {
                if (! Schema::hasColumn('consultations', $key)) {
                    Schema::table('consultations', function (Blueprint $table) use ($key) {
                        $table->string($key, 255)->nullable()->after('whatsapp_e164');
                    });
                }
            }

            foreach ($this->dedupeColumns as $source => $key) {
                $this->populateDedupeKeys($source, $key);
            }

            foreach ($this->uniqueIndexes as $name => $column) {
                $this->addUniqueIndex($column, $name);
            }
        }

        if (! Schema::hasTable('konsultasi_saved_filters')) {
            Schema::create('konsultasi_saved_filters', function (Blueprint $table) {
                $table->id();
                $table->uuid('user_id');
                $table->string('name', 100);
                $table->json('filters');
                $table->boolean('is_default')->default(false);
                $table->timestamps();

                $table->unique(['user_id', 'name'], 'konsultasi_saved_filters_user_name_unique');
                $table->index(['user_id', 'is_default'], 'konsultasi_saved_filters_user_default_idx');
                $table->foreign('user_id')->references('uuid')->on('users')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('consultations')) {
            foreach (array_keys($this->uniqueIndexes) as $name) {
                $this->dropIndexIfExists($name);
            }

            $columns = array_values(array_filter(array_merge(
                ['deleted_at', 'sort_order'],
                array_values($this->dedupeColumns)
            ), fn ($column) => Schema::hasColumn('consultations', $column)));

            if (! empty($columns)) {
                Schema::table('consultations', fn (Blueprint $table) => $table->dropColumn($columns));
            }
        }

        Schema::dropIfExists('konsultasi_saved_filters');
    }

    private function populateDedupeKeys(string $source, string $key): void
    {
        DB::table('consultations')
            ->select('uuid', $source)
            ->whereNotNull($source)
            ->where($source, '!=', '')
            ->orderBy('created_at')
            ->chunk(500, function ($rows) use ($source, $key) {
                $seen = [];

                foreach ($rows as $row) {
                    $value = (string) $row->{$source};

                    if (isset($seen[$value])) {
                        continue;
                    }

                    $seen[$value] = true;

                    DB::table('consultations')
                        ->where('uuid', $row->uuid)
                        ->update([$key => $value]);
                }
            });
    }

    private function addUniqueIndex(string $column, string $name): void
    {
        if ($this->indexExists($name)) {
            return;
        }

        Schema::table('consultations', fn (Blueprint $table) => $table->unique($column, $name));
    }

    private function dropIndexIfExists(string $name): void
    {
        if (! $this->indexExists($name)) {
            return;
        }

        Schema::table('consultations', fn (Blueprint $table) => $table->dropUnique($name));
    }

    private function indexExists(string $name): bool
    {
        return DB::table('INFORMATION_SCHEMA.STATISTICS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'consultations')
            ->where('INDEX_NAME', $name)
            ->exists();
    }
};
