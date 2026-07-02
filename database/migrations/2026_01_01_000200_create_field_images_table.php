<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('field_images', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('football_field_id')
                ->constrained('football_fields')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('image_url');
            $table->boolean('is_main')->default(false)->index();
            $table->timestamps();

            $table->index(['football_field_id', 'is_main']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_images');
    }
};
