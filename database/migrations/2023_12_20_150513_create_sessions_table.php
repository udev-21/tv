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
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->timestamp('in');
            $table->timestamp('out')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'in', 'out']);
            $table->index(['user_id', 'in']);
            $table->index(['user_id', 'out']);
            $table->index(['user_id']);
            $table->index(['in', 'out']);
            $table->index(['in']);
            $table->index(['out']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
