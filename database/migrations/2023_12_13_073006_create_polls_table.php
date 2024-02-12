<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->string('color', 7);
            $table->longText('story')->nullable();
            $table->boolean('multiple_choice')->default(0);
            $table->boolean('published')->default(0);
            $table->enum('language', ['en', 'bn'])->default('bn');
            $table->json('poll_options');
            $table->json('votes')->nullable();
            $table->dateTime('expire_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('vote_signs', function (Blueprint $table) {
            $table->id();
            $table->string('voted_poll_ids')->nullable();
            $table->foreignId('voter')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('polls');
        Schema::dropIfExists('vote_signs');
    }
};
