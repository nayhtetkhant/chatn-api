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
        Schema::create('friends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userA_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('userB_id')->constrained('users')->cascadeOnDelete();
            $table->string('type')->default('commonFriend');
            $table->unique(['userA_id' , 'userB_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friends');
    }
};
