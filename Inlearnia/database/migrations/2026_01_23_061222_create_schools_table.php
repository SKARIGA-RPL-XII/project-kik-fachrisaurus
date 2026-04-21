<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('npsn', 20)->nullable(); // tambahan
            $table->text('address')->nullable();
            $table->string('principal_name')->nullable(); // tambahan
            $table->string('phone', 20)->nullable(); // tambahan
            $table->string('email')->nullable(); // tambahan
            $table->string('level')->nullable(); // tambahan
            $table->string('status')->nullable(); // tambahan
            $table->string('accreditation', 5)->nullable(); // tambahan
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};