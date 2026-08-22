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
        Schema::create('friend_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('initiator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approver_id')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->unique(['initiator_id' , 'approver_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friend_requests');
    }
};
