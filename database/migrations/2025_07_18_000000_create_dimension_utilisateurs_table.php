<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('dimension_utilisateurs', function (Blueprint $table) {
            $table->string('id_utilisateur', 50)->primary();
            $table->string('ville', 100)->nullable();
            $table->string('age', 20)->nullable();
            $table->string('genre', 20)->nullable();
            $table->date('date_inscription')->nullable();
            $table->string('nom', 100)->nullable();
            $table->string('email', 100)->unique();
            $table->string('password', 100);
            $table->boolean('email_verified')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('dimension_utilisateurs');
    }
};
