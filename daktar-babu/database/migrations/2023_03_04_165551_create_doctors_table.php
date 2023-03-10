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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->id();
            $table->unsignedInteger('doc_id');
            $table->string('category')->nullable();
            $table->unsignedInteger('patients')->nullable();
            $table->unsignedInteger('experience')->nullable();
            $table->longText('experience')->nullable();
            $table->string('status')->nullable();
            $table->foreign('doc_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('doctors');
    }
};
