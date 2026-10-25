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
        Schema::table('invitations', function (Blueprint $table) {
            $table->string('live_streaming_url')->nullable()->after('google_maps_url');
            $table->string('music_url')->nullable()->after('live_streaming_url');
            $table->text('dresscode')->nullable()->after('music_url');
            $table->string('custom_font')->nullable()->after('dresscode');
            $table->json('love_stories')->nullable()->after('custom_font');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropColumn([
                'live_streaming_url',
                'music_url',
                'dresscode',
                'custom_font',
                'love_stories',
            ]);
        });
    }
};
