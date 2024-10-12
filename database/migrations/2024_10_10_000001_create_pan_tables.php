<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pan\Enums\EventType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pan_analytics', function (Blueprint $table): void {
            $table->id();
            $table->string('name');

            collect(EventType::cases())->each(fn (EventType $case) => $table->unsignedBigInteger($case->column())->default(0));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pan_analytics');
    }
};
