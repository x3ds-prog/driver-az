<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone');
            $table->string('pickup_address')->nullable();
            $table->string('destination_address')->nullable();
            $table->string('tariff_zone')->nullable();
            $table->string('car_type')->nullable();
            $table->text('comment')->nullable();
            $table->string('locale', 5)->default('az');
            $table->string('status', 20)->default('new')->index();
            $table->string('ip_address', 45)->nullable();
            $table->string('country_code', 8)->nullable();
            $table->string('device_name')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_requests');
    }
};
