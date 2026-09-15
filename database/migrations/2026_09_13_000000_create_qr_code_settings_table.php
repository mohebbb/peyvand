<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_code_settings', function (Blueprint $table) {
            $table->id();
            $table->string('format', 10)->default('svg');
            $table->unsignedInteger('size')->default(300);
            $table->unsignedTinyInteger('margin')->default(2);
            $table->string('error_correction', 1)->default('H');
            $table->string('foreground_color', 9)->default('#000000');
            $table->string('background_color', 9)->default('#ffffff');
            $table->boolean('transparent_background')->default(false);
            $table->boolean('logo_enabled')->default(false);
            $table->string('logo_path')->nullable();
            $table->unsignedTinyInteger('logo_size_percent')->default(20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_code_settings');
    }
};
