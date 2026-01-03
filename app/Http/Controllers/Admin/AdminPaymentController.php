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

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(15);
        return view('admin.transactions-list', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['appointment.patient.user', 'appointment.psychologist.user', 'verifier']);
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
}
