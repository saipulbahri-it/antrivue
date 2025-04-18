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
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->string('number'); // e.g., A001
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('counter_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('status', ['waiting', 'calling', 'done', 'skipped'])->default('waiting');
            $table->timestamp('called_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
