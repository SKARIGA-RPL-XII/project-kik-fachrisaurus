<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    // php artisan make:migration add_score_feedback_to_submissions_table
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->integer('score')->nullable()->after('submitted_at');
            $table->text('feedback')->nullable()->after('score');
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(['score', 'feedback']);
        });
    }
};
