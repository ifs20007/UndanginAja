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
        Schema::table('plans', function (Blueprint $table) {
            $table->unsignedInteger('wa_blast_quota')->default(0)->after('max_invitations');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('wa_blast_quota')->default(0)->after('subscription_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('wa_blast_quota');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('wa_blast_quota');
        });
    }
};
