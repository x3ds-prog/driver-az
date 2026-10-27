<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_hash', 64)->index();
            $table->string('ip_address', 45)->nullable();
            $table->string('country_code', 8)->nullable();
            $table->string('device_type', 20)->nullable()->index();
            $table->string('device_brand_model')->nullable();
            $table->string('os')->nullable();
            $table->string('browser')->nullable();
            $table->string('locale', 5)->nullable();
            $table->string('path')->nullable();
            $table->string('referer')->nullable();
            $table->boolean('is_bot')->default(false)->index();
            $table->date('visited_date')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_visits');
    }
};
