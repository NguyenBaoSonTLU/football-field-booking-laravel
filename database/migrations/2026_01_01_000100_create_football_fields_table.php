<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('football_fields', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->text('description')->nullable();
            $table->json('amenities')->nullable();
            $table->decimal('price_per_hour', 12, 2);
            $table->time('open_time');
            $table->time('close_time');
            $table->string('status', 30)->default('active')->index();
            $table->timestamps();

            $table->index(['name', 'status']);
            $table->index(['price_per_hour', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('football_fields');
    }
};
