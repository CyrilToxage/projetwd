<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('question_options', function (Blueprint $table) {
            $table->foreignId('phrase_id')->nullable()->after('question_id')->constrained('phrases')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('question_options', function (Blueprint $table) {
            $table->dropForeign(['phrase_id']);
            $table->dropColumn('phrase_id');
        });
    }
};
