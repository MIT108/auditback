<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courier_departments', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->foreignId('courier_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('departement_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courier_departments');
    }
};
