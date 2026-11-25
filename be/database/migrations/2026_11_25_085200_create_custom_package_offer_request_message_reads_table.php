<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_package_offer_request_message_reads', function (Blueprint $table): void {
            $table->id();
            $table->uuid('message_uuid');
            $table->uuid('user_uuid');
            $table->timestamp('read_at');
            $table->timestamps();

            $table->foreign('message_uuid', 'cpormr_message_fk')
                ->references('uuid')
                ->on('custom_package_offer_request_messages')
                ->cascadeOnDelete();
            $table->foreign('user_uuid', 'cpormr_user_fk')
                ->references('uuid')
                ->on('users')
                ->cascadeOnDelete();
            $table->unique(['message_uuid', 'user_uuid'], 'cpormr_message_user_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_package_offer_request_message_reads');
    }
};
