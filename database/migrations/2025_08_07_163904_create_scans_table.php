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
        Schema::create('scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained();
            $table->string('name')->comment('The name of the scan.')->index();
            $table->jsonb('configuration')->comment('The configuration of the scan.');
            $table->jsonb('media_frames_data')->comment('The frames data of the media file')->nullable()->default(null);
            $table->jsonb('media_frames_analyzation_data')->comment('The frames analyzation data of the media file')->nullable()->default(null);
            $table->jsonb('anomalies_data')->comment('The anomalies data of the media file frames.')->nullable()->default(null);
            $table->jsonb('violations_data')->comment('The violations data of the media file.')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scans');
    }
};
