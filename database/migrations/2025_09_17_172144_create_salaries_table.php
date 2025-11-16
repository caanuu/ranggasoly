<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->integer('present_rate')->default(100000);
            $table->integer('sick_rate')->default(70000);
            $table->integer('absent_rate')->default(-100000);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('salary_settings');
    }
};

