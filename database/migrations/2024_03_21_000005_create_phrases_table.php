<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('phrases', function (Blueprint $table) {
            $table->id();
            $table->text('texte');
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
        Schema::dropIfExists('phrases');
    }
};
