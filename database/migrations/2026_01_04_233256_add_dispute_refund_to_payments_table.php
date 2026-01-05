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
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('dispute_status', ['none', 'disputed', 'resolved'])->default('none')->after('rejection_reason');
            $table->text('dispute_reason')->nullable()->after('dispute_status');
            $table->timestamp('disputed_at')->nullable()->after('dispute_reason');
            $table->foreignId('disputed_by')->nullable()->constrained('users')->onDelete('set null')->after('disputed_at');
            $table->text('dispute_resolution')->nullable()->after('disputed_by');
            $table->timestamp('dispute_resolved_at')->nullable()->after('dispute_resolution');
            $table->foreignId('dispute_resolved_by')->nullable()->constrained('users')->onDelete('set null')->after('dispute_resolved_at');
            
            $table->enum('refund_status', ['none', 'requested', 'approved', 'rejected', 'processed'])->default('none')->after('dispute_resolved_by');
            $table->text('refund_reason')->nullable()->after('refund_status');
            $table->decimal('refund_amount', 10, 2)->nullable()->after('refund_reason');
            $table->timestamp('refund_requested_at')->nullable()->after('refund_amount');
            $table->foreignId('refund_requested_by')->nullable()->constrained('users')->onDelete('set null')->after('refund_requested_at');
            $table->timestamp('refund_processed_at')->nullable()->after('refund_requested_by');
            $table->foreignId('refund_processed_by')->nullable()->constrained('users')->onDelete('set null')->after('refund_processed_at');
            $table->text('refund_notes')->nullable()->after('refund_processed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['disputed_by']);
            $table->dropForeign(['dispute_resolved_by']);
            $table->dropForeign(['refund_requested_by']);
            $table->dropForeign(['refund_processed_by']);
            $table->dropColumn([
                'dispute_status',
                'dispute_reason',
                'disputed_at',
                'disputed_by',
                'dispute_resolution',
                'dispute_resolved_at',
                'dispute_resolved_by',
                'refund_status',
                'refund_reason',
                'refund_amount',
                'refund_requested_at',
                'refund_requested_by',
                'refund_processed_at',
                'refund_processed_by',
                'refund_notes',
            ]);
        });
    }
};
