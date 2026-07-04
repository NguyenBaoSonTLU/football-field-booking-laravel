<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('football_field_id')
                ->constrained('football_fields')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('time_slot_id')
                ->constrained('time_slots')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->date('booking_date');
            $table->decimal('total_price', 12, 2);
            $table->string('status', 30)->default('pending')->index();
            $table->text('note')->nullable();
            $table->string('slot_lock_key', 100)->nullable()->unique();
            $table->timestamps();

            $table->index(['football_field_id', 'booking_date', 'time_slot_id'], 'bookings_availability_index');
            $table->index(['user_id', 'status', 'booking_date'], 'bookings_user_history_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
