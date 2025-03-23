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
            if (Schema::hasColumn('reponses', 'session_id')) {
                $table->dropColumn('session_id');
            }
            if (!Schema::hasColumn('reponses', 'session_token')) {
                $table->string('session_token', 32)->after('question_id')->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reponses', function (Blueprint $table) {
            if (Schema::hasColumn('reponses', 'session_token')) {
                $table->dropColumn('session_token');
            }
            if (!Schema::hasColumn('reponses', 'session_id')) {
                $table->string('session_id', 32)->after('question_id')->index();
            }
        });
    }
};
