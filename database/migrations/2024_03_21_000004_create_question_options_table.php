<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->string('texte');
            $table->unsignedBigInteger('question_id');
            $table->timestamps();

            $table->foreign('question_id')
                ->references('id')
                ->on('questions')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('question_options');
    }
};
