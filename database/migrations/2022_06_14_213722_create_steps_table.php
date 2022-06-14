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
        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['A', 'I', 'E'])->default('A');

            $table->unsignedBigInteger('user_create')->nullable();
            $table->foreign('user_create')->references('id')->on('users')->onDelete('set null');

            $table->unsignedBigInteger('user_modifies')->nullable();
            $table->foreign('user_modifies')->references('id')->on('users')->onDelete('set null');

            $table->unsignedBigInteger('user_delete')->nullable();
            $table->foreign('user_delete')->references('id')->on('users')->onDelete('set null');

            $table->unsignedBigInteger('subsection_id');
            $table->foreign('subsection_id')->references('id')->on('subsections')->onDelete('cascade');

            $table->dateTime('date_delete')->nullable();
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
        Schema::dropIfExists('steps');
    }
};