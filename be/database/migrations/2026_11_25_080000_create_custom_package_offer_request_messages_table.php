<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_package_offer_request_messages', function (Blueprint $table): void {
            $table->uuid('uuid')->primary();
            $table->uuid('request_uuid');
            $table->uuid('actor_uuid')->nullable();
            $table->text('body');
            $table->timestamps();

            $table->foreign('request_uuid', 'cporm_request_fk')
                ->references('uuid')
                ->on('custom_package_offer_requests')
                ->cascadeOnDelete();
            $table->foreign('actor_uuid', 'cporm_actor_fk')
                ->references('uuid')
                ->on('users')
                ->nullOnDelete();
            $table->index(['request_uuid', 'created_at'], 'cporm_request_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_package_offer_request_messages');
    }
};
