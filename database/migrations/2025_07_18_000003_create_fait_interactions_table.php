<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('fait_interactions', function (Blueprint $table) {
            $table->bigIncrements('id_interaction');
            $table->string('id_utilisateur', 50);
            $table->string('id_entreprise', 50);
            $table->date('date_interaction');
            $table->string('type_interaction', 50)->nullable();
            $table->integer('duree_vue')->nullable();
            $table->integer('note_avis')->nullable();
            $table->foreign('id_utilisateur')->references('id_utilisateur')->on('dimension_utilisateurs');
            $table->foreign('id_entreprise')->references('id_entreprise')->on('dimension_entreprises');
            $table->foreign('date_interaction')->references('date_id')->on('dimension_temps');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fait_interactions');
    }
};
