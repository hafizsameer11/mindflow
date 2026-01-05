<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PatientPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:patient']);
    }

    public function create(Appointment $appointment)
    {
        $patient = Auth::user()->patient;

        if ($appointment->patient_id !== $patient->id) {
            abort(403, 'Unauthorized');
        }

        if ($appointment->payment) {
            return redirect()->route('patient.appointments.show', $appointment)
                ->withInfo('Payment already uploaded.');
        }

        $appointment->load('psychologist.user');
        
        return view('checkout', compact('appointment'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        $patient = Auth::user()->patient;

        if ($appointment->patient_id !== $patient->id) {
            abort(403, 'Unauthorized');
        }

        if ($appointment->payment) {
            return redirect()->back()->withErrors('Payment already uploaded.');
        }

        $request->validate([
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'bank_name' => 'required|string|max:255',
            'transaction_id' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        // Verify amount matches consultation fee
        if ($request->amount != $appointment->psychologist->consultation_fee) {
            return redirect()->back()->withErrors('Payment amount does not match consultation fee.');
        }

        // Store receipt
        $receiptPath = $request->file('receipt')->store('receipts', 'public');

        $payment = Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => $request->amount,
            'receipt_file_path' => $receiptPath,
            'bank_name' => $request->bank_name,
            'transaction_id' => $request->transaction_id,
            'status' => 'pending_verification',
            'uploaded_at' => now(),
        ]);

        // Send notification
        app(NotificationService::class)->notifyPaymentUploaded($payment);

        return redirect()->route('patient.appointments.show', $appointment)
            ->withSuccess('Payment receipt uploaded successfully. Waiting for admin verification.');
    }

    public function index()
    {
        $patient = Auth::user()->patient;
        
        if (!$patient) {
            return redirect()->route('patient.dashboard')->withErrors('Patient profile not found.');
        }
        
        $payments = Payment::with(['appointment.psychologist.user'])
            ->whereHas('appointment', function($query) use ($patient) {
                $query->where('patient_id', $patient->id);
            })
            ->latest()
            ->get();

        return view('patient.payments.index', compact('payments'));
    }

    public function downloadReceipt(Payment $payment)
    {
        $patient = Auth::user()->patient;

        if (!$patient || $payment->appointment->patient_id !== $patient->id) {
            abort(403, 'Unauthorized');
        }

        if (!$payment->receipt_file_path || !Storage::disk('public')->exists($payment->receipt_file_path)) {
            abort(404, 'Receipt not found');
        }

        $filePath = Storage::disk('public')->path($payment->receipt_file_path);
        $fileName = 'receipt-' . $payment->id . '.' . pathinfo($filePath, PATHINFO_EXTENSION);

        return response()->download($filePath, $fileName);
    }
}
