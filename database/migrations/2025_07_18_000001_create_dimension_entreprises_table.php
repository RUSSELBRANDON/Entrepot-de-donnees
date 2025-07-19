<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('dimension_entreprises', function (Blueprint $table) {
            $table->string('id_entreprise', 50)->primary();
            $table->string('nom_entreprise', 100)->nullable();
            $table->string('secteur', 100)->nullable();
            $table->string('ville', 100)->nullable();
            $table->string('type_abonnement', 50)->nullable();
            $table->string('statut_paiement', 20)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dimension_entreprises');
    }
};
