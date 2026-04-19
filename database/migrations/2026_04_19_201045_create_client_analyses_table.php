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
        Schema::create('client_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interaction_id')->constrained()->cascadeOnDelete();
            $table->string('client_profile')->nullable();
            $table->string('client_type')->nullable();
            $table->string('interest_level')->nullable();
            $table->string('price_sensitivity')->nullable();
            $table->string('trust_level')->nullable();
            $table->integer('buying_probability')->nullable();
            $table->string('main_objection')->nullable();
            $table->string('psychological_trigger')->nullable();
            $table->string('recommended_strategy')->nullable();
            $table->string('next_best_action')->nullable();
            $table->text('suggested_message')->nullable();
            $table->string('follow_up_urgency')->nullable();
            $table->integer('analysis_confidence')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_analyses');
    }
};
