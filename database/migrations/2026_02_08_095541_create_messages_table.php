<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->forigein('conversation_id')->constrained();

            $table->unsignedBigInteger('sender_id');
            $table->forigein('sender_id')->references('id')->on('users');

            $twble->unsignedBigInteger('receiver_id');
            $table->forigein('receiver_id')->references('id')->on('users');

            $table->timestamps('read_at')->nullable();

            //deleted_actions

            $table->timestamps('receiver_deleted_at')->nullable();
            $table->timestamps('sender_deleted_at')->nullable();

            $table->text('body')->nullable();


            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
