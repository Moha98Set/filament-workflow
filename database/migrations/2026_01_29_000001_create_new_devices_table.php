<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('new_devices', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('operator_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('new_devices');
    }
};
