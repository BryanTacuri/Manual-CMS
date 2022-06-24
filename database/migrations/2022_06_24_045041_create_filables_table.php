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
        Schema::create('filables', function (Blueprint $table) {
            $table->unsignedBigInteger('filable_id');
            $table->string('filable_type');
            $table->unsignedBigInteger('category_id');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');

            $table->unsignedBigInteger('user_create')->nullable();
            $table->foreign('user_create')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('user_modifies')->nullable();
            $table->foreign('user_modifies')->references('id')->on('users')->onDelete('set null');

            $table->primary(['filable_id', 'filable_type', 'file_id']);

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
        Schema::dropIfExists('filables');
    }
};