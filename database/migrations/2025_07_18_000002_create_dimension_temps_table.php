<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('dimension_temps', function (Blueprint $table) {
            $table->date('date_id')->primary();
            $table->integer('jour')->nullable();
            $table->integer('mois')->nullable();
            $table->integer('annee')->nullable();
            $table->string('jour_semaine', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dimension_temps');
    }
};
