<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reponses', function (Blueprint $table) {
            $table->foreignId('phrase_id')->nullable()->after('question_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reponses', function (Blueprint $table) {
            $table->dropForeign(['phrase_id']);
            $table->dropColumn('phrase_id');
        });
    }
};
