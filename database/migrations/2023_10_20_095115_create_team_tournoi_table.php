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
        Schema::create('team_tournoi', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Team::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Tournoi::class)->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['team_id', 'tournoi_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_tournoi');
    }
};
