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
        Schema::table('formulaires', function (Blueprint $table) {
            // Vérifier si la colonne existe avant de la supprimer
            if (Schema::hasColumn('formulaires', 'module_id')) {
                // Supprimer la clé étrangère si elle existe
                $foreignKeys = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableForeignKeys('formulaires');

                foreach ($foreignKeys as $foreignKey) {
                    if ($foreignKey->getLocalColumns() === ['module_id']) {
                        $table->dropForeign(['module_id']);
                        break;
                    }
                }

                $table->dropColumn('module_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formulaires', function (Blueprint $table) {
            if (!Schema::hasColumn('formulaires', 'module_id')) {
                $table->foreignId('module_id')->nullable()->constrained()->onDelete('cascade');
            }
        });
    }
};
