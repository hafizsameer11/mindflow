<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = Payment::with(['appointment.patient.user', 'appointment.psychologist.user']);

        // Filter by payment status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by dispute status
        if ($request->filled('dispute_status')) {
            $query->where('dispute_status', $request->dispute_status);
        }

        // Filter by refund status
        if ($request->filled('refund_status')) {
            $query->where('refund_status', $request->refund_status);
        }

        // Search by patient or psychologist name/email, transaction ID, or payment ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('appointment.patient.user', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('appointment.psychologist.user', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->latest()->get();

        // Calculate financial statistics
        $stats = [
            'total_payments' => Payment::count(),
            'total_amount' => Payment::sum('amount'),
            'verified_amount' => Payment::where('status', 'verified')->sum('amount'),
            'pending_amount' => Payment::where('status', 'pending_verification')->sum('amount'),
            'rejected_count' => Payment::where('status', 'rejected')->count(),
            'verified_count' => Payment::where('status', 'verified')->count(),
            'pending_count' => Payment::where('status', 'pending_verification')->count(),
            'disputed_count' => Payment::where('dispute_status', 'disputed')->count(),
            'refund_requested_count' => Payment::where('refund_status', 'requested')->count(),
            'refund_processed_amount' => Payment::where('refund_status', 'processed')->sum('refund_amount'),
        ];

        return view('admin.transactions-list', compact('payments', 'stats'));
    }

    public function show(Payment $payment)
    {
        $payment->load([
            'appointment.patient.user', 
            'appointment.psychologist.user', 
            'verifier',
            'disputer',
            'disputeResolver',
            'refundRequester',
            'refundProcessor'
        ]);
        return view('admin.payments.show', compact('payment'));
    }

    public function verify(Payment $payment)
    {
        $payment->update([
            'status' => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // Update appointment status to confirmed if payment is verified
        $payment->appointment->update(['status' => 'confirmed']);

        // Send notification
        app(NotificationService::class)->notifyPaymentVerified($payment);
        app(NotificationService::class)->notifyAppointmentConfirmed($payment->appointment);

        return redirect()->back()->withSuccess('Payment verified successfully.');
    }

    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $payment->update([
            'status' => 'rejected',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Send notification
        app(NotificationService::class)->notifyPaymentRejected($payment);

        return redirect()->back()->withSuccess('Payment rejected.');
    }

    public function downloadReceipt(Payment $payment)
    {
        if (!$payment->receipt_file_path || !Storage::disk('public')->exists($payment->receipt_file_path)) {
            abort(404, 'Receipt not found');
        }

        return Storage::disk('public')->download($payment->receipt_file_path);
    }

    public function dispute(Request $request, Payment $payment)
    {
        $request->validate([
            'dispute_reason' => 'required|string|min:10',
        ]);

        $payment->update([
            'dispute_status' => 'disputed',
            'dispute_reason' => $request->dispute_reason,
            'disputed_at' => now(),
            'disputed_by' => Auth::id(),
        ]);

        // Send notification
        app(NotificationService::class)->notifyPaymentDisputed($payment);

        return redirect()->back()->withSuccess('Payment dispute recorded successfully.');
    }

    public function resolveDispute(Request $request, Payment $payment)
    {
        $request->validate([
            'dispute_resolution' => 'required|string|min:10',
        ]);

        $payment->update([
            'dispute_status' => 'resolved',
            'dispute_resolution' => $request->dispute_resolution,
            'dispute_resolved_at' => now(),
            'dispute_resolved_by' => Auth::id(),
        ]);

        // Send notification
        app(NotificationService::class)->notifyDisputeResolved($payment);

        return redirect()->back()->withSuccess('Dispute resolved successfully.');
    }

    public function requestRefund(Request $request, Payment $payment)
    {
        $request->validate([
            'refund_reason' => 'required|string|min:10',
            'refund_amount' => 'required|numeric|min:0|max:' . $payment->amount,
        ]);

        $payment->update([
            'refund_status' => 'requested',
            'refund_reason' => $request->refund_reason,
            'refund_amount' => $request->refund_amount,
            'refund_requested_at' => now(),
            'refund_requested_by' => Auth::id(),
        ]);

        // Send notification
        app(NotificationService::class)->notifyRefundRequested($payment);

        return redirect()->back()->withSuccess('Refund request submitted successfully.');
    }

    public function approveRefund(Request $request, Payment $payment)
    {
        $request->validate([
            'refund_notes' => 'nullable|string',
        ]);

        $payment->update([
            'refund_status' => 'approved',
            'refund_notes' => $request->refund_notes,
        ]);

        // Send notification
        app(NotificationService::class)->notifyRefundApproved($payment);

        return redirect()->back()->withSuccess('Refund approved successfully.');
    }

    public function rejectRefund(Request $request, Payment $payment)
    {
        $request->validate([
            'refund_notes' => 'required|string|min:10',
        ]);

        $payment->update([
            'refund_status' => 'rejected',
            'refund_notes' => $request->refund_notes,
        ]);

        // Send notification
        app(NotificationService::class)->notifyRefundRejected($payment);

        return redirect()->back()->withSuccess('Refund request rejected.');
    }

    public function processRefund(Request $request, Payment $payment)
    {
        $request->validate([
            'refund_notes' => 'nullable|string',
        ]);

        if ($payment->refund_status !== 'approved') {
            return redirect()->back()->withErrors('Refund must be approved before processing.');
        }

        $payment->update([
            'refund_status' => 'processed',
            'refund_processed_at' => now(),
            'refund_processed_by' => Auth::id(),
            'refund_notes' => $request->refund_notes ?? $payment->refund_notes,
        ]);

        // Send notification
        app(NotificationService::class)->notifyRefundProcessed($payment);

        return redirect()->back()->withSuccess('Refund processed successfully.');
    }
}
