<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('formulaires', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->boolean('actif')->default(true);
            $table->string('token')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('formulaires');
    }
};
