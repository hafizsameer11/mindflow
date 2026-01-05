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
        Schema::table('appointments', function (Blueprint $table) {
            $table->text('session_notes')->nullable()->after('notes');
            $table->text('diagnosis')->nullable()->after('session_notes');
            $table->text('observations')->nullable()->after('diagnosis');
            $table->text('follow_up_recommendations')->nullable()->after('observations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['session_notes', 'diagnosis', 'observations', 'follow_up_recommendations']);
        });
    }
};
