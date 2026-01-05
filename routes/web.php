<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\AuthCustomController;
use App\Http\Controllers\Patient\AuthController as PatientAuthController;
use App\Http\Controllers\Psychologist\AuthController as PsychologistAuthController;
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\Patient\PatientSearchController;
use App\Http\Controllers\Patient\PatientAppointmentController;
use App\Http\Controllers\Patient\PatientPaymentController;
use App\Http\Controllers\Patient\PatientSessionController;
use App\Http\Controllers\Patient\PatientPrescriptionController;
use App\Http\Controllers\Patient\PatientFeedbackController;
use App\Http\Controllers\Psychologist\PsychologistController;
use App\Http\Controllers\Psychologist\PsychologistAppointmentController;
use App\Http\Controllers\Psychologist\PsychologistAvailabilityController;
use App\Http\Controllers\Psychologist\PsychologistPrescriptionController;
use App\Http\Controllers\Psychologist\PsychologistSessionController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminPsychologistController;
use App\Http\Controllers\Admin\AdminAppointmentController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminFeedbackController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminBackupController;

// Public Routes
Route::get('/', function () {
    return view('index');
})->name('index');

// Debug route - REMOVE IN PRODUCTION
Route::get('/test-user/{email}', function ($email) {
    $user = \App\Models\User::where('email', $email)->first();
    if ($user) {
        return response()->json([
            'found' => true,
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'password_hash_length' => strlen($user->password),
            'password_hash_prefix' => substr($user->password, 0, 20)
        ]);
    }
    return response()->json(['found' => false, 'searched_email' => $email]);
})->name('test.user');

// Authentication Routes
Route::get('admin/login', [CustomAuthController::class, 'index'])->name('admin.login');
Route::post('admin/login', [CustomAuthController::class, 'customLogin'])->name('admin.login.post');
Route::get('admin/signout', [CustomAuthController::class, 'signOut'])->name('admin.signout');

Route::get('patient/login', [PatientAuthController::class, 'showLoginForm'])->name('patient.login');
Route::post('patient/login', [PatientAuthController::class, 'login'])->name('patient.login.post');
Route::get('patient/register', [PatientAuthController::class, 'showRegistrationForm'])->name('patient.register');
Route::post('patient/register', [PatientAuthController::class, 'register'])->name('patient.register.post');
Route::post('patient/logout', [PatientAuthController::class, 'logout'])->name('patient.logout');

Route::get('psychologist/login', [PsychologistAuthController::class, 'showLoginForm'])->name('psychologist.login');
Route::post('psychologist/login', [PsychologistAuthController::class, 'login'])->name('psychologist.login.post');
Route::get('psychologist/register', [PsychologistAuthController::class, 'showRegistrationForm'])->name('psychologist.register');
Route::post('psychologist/register', [PsychologistAuthController::class, 'register'])->name('psychologist.register.post');
Route::post('psychologist/logout', [PsychologistAuthController::class, 'logout'])->name('psychologist.logout');

// Notification Routes (for all authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::post('notifications/{notification}/read', function ($notificationId) {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.read');
    
    Route::post('notifications/mark-all-read', function () {
        Auth::user()->unreadNotifications()->where('type', 'App\Notifications\AdminAnnouncementNotification')->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    })->name('notifications.mark-all-read');
});

// Patient Routes
Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
    Route::get('profile', [PatientController::class, 'profile'])->name('profile');
    Route::post('profile', [PatientController::class, 'updateProfile'])->name('profile.update');
    Route::post('profile/password', [PatientController::class, 'updatePassword'])->name('profile.update-password');
    Route::post('profile/preferences', [PatientController::class, 'updatePreferences'])->name('profile.update-preferences');
    
    Route::get('search', [PatientSearchController::class, 'index'])->name('search');
    Route::get('psychologist/{psychologist}', [PatientSearchController::class, 'show'])->name('psychologist.show');
    
    Route::get('appointments', [PatientAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/{appointment}', [PatientAppointmentController::class, 'show'])->name('appointments.show');
    Route::get('psychologist/{psychologist}/book', [PatientAppointmentController::class, 'create'])->name('appointments.create');
    Route::post('psychologist/{psychologist}/book', [PatientAppointmentController::class, 'store'])->name('appointments.store');
    Route::post('appointments/{appointment}/cancel', [PatientAppointmentController::class, 'cancel'])->name('appointments.cancel');
    
    Route::get('appointments/{appointment}/payment', [PatientPaymentController::class, 'create'])->name('payment.create');
    Route::post('appointments/{appointment}/payment', [PatientPaymentController::class, 'store'])->name('payment.store');
    Route::get('payments', [PatientPaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}/receipt', [PatientPaymentController::class, 'downloadReceipt'])->name('payments.receipt');
    
    Route::get('appointments/{appointment}/join', [PatientSessionController::class, 'join'])->name('session.join');
    
    Route::get('prescriptions', [PatientPrescriptionController::class, 'index'])->name('prescriptions.index');
    Route::get('prescriptions/{prescription}', [PatientPrescriptionController::class, 'show'])->name('prescriptions.show');
    
    Route::get('feedback', [PatientFeedbackController::class, 'index'])->name('feedback.index');
    Route::get('feedback/{feedback}', [PatientFeedbackController::class, 'show'])->name('feedback.show');
    Route::get('appointments/{appointment}/feedback', [PatientFeedbackController::class, 'create'])->name('feedback.create');
    Route::post('appointments/{appointment}/feedback', [PatientFeedbackController::class, 'store'])->name('feedback.store');
    Route::get('feedback/{feedback}/edit', [PatientFeedbackController::class, 'edit'])->name('feedback.edit');
    Route::put('feedback/{feedback}', [PatientFeedbackController::class, 'update'])->name('feedback.update');
});

// Psychologist Routes
Route::middleware(['auth', 'role:psychologist'])->prefix('psychologist')->name('psychologist.')->group(function () {
    Route::get('dashboard', [PsychologistController::class, 'dashboard'])->name('dashboard');
    Route::get('profile', [PsychologistController::class, 'profile'])->name('profile');
    Route::post('profile', [PsychologistController::class, 'updateProfile'])->name('profile.update');
    Route::get('qualification/{index}', [PsychologistController::class, 'downloadQualification'])->name('qualification.download');
    Route::get('qualification/{index}/view', [PsychologistController::class, 'viewQualification'])->name('qualification.view');
    Route::get('my-patients', [PsychologistController::class, 'myPatients'])->name('my-patients');
    
    Route::get('appointments', [PsychologistAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/{appointment}', [PsychologistAppointmentController::class, 'show'])->name('appointments.show');
    Route::post('appointments/{appointment}/confirm', [PsychologistAppointmentController::class, 'confirm'])->name('appointments.confirm');
    Route::post('appointments/{appointment}/cancel', [PsychologistAppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::post('appointments/{appointment}/reschedule', [PsychologistAppointmentController::class, 'reschedule'])->name('appointments.reschedule');
    Route::post('payments/{payment}/verify', [PsychologistAppointmentController::class, 'verifyPayment'])->name('payments.verify');
    Route::post('payments/{payment}/reject', [PsychologistAppointmentController::class, 'rejectPayment'])->name('payments.reject');
    
    Route::get('availability', [PsychologistAvailabilityController::class, 'index'])->name('availability.index');
    Route::post('availability', [PsychologistAvailabilityController::class, 'store'])->name('availability.store');
    Route::post('availability/bulk', [PsychologistAvailabilityController::class, 'bulkStore'])->name('availability.bulk-store');
    Route::post('availability/copy-day', [PsychologistAvailabilityController::class, 'copyDay'])->name('availability.copy-day');
    Route::post('availability/delete-day', [PsychologistAvailabilityController::class, 'deleteDay'])->name('availability.delete-day');
    Route::put('availability/{availability}', [PsychologistAvailabilityController::class, 'update'])->name('availability.update');
    Route::delete('availability/{availability}', [PsychologistAvailabilityController::class, 'destroy'])->name('availability.destroy');
    
    Route::get('prescriptions', [PsychologistPrescriptionController::class, 'index'])->name('prescriptions.index');
    Route::get('appointments/{appointment}/prescription/create', [PsychologistPrescriptionController::class, 'create'])->name('prescriptions.create');
    Route::post('appointments/{appointment}/prescription', [PsychologistPrescriptionController::class, 'store'])->name('prescriptions.store');
    Route::get('prescriptions/{prescription}/edit', [PsychologistPrescriptionController::class, 'edit'])->name('prescriptions.edit');
    Route::put('prescriptions/{prescription}', [PsychologistPrescriptionController::class, 'update'])->name('prescriptions.update');
    
    Route::get('appointments/{appointment}/start', [PsychologistSessionController::class, 'start'])->name('session.start');
    Route::post('appointments/{appointment}/end', [PsychologistSessionController::class, 'end'])->name('session.end');
    Route::post('appointments/{appointment}/save-notes', [PsychologistSessionController::class, 'saveNotes'])->name('session.save-notes');
    
    Route::get('feedback', [\App\Http\Controllers\Psychologist\PsychologistFeedbackController::class, 'index'])->name('feedback.index');
    Route::get('feedback/{feedback}', [\App\Http\Controllers\Psychologist\PsychologistFeedbackController::class, 'show'])->name('feedback.show');
    
    Route::get('earnings', [\App\Http\Controllers\Psychologist\PsychologistEarningsController::class, 'index'])->name('earnings.index');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('index_admin', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::resource('users', AdminUserController::class);
    Route::post('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::get('psychologists', [AdminPsychologistController::class, 'index'])->name('psychologists.index');
    Route::get('doctor-list', [AdminPsychologistController::class, 'index'])->name('doctor-list'); // Alias for backward compatibility
    Route::get('patient-list', [AdminController::class, 'patientList'])->name('patient-list');
    Route::get('specialities', [AdminController::class, 'specialities'])->name('specialities');
    Route::get('register', function() {
        return view('admin.register');
    })->name('register');
    Route::get('psychologists/{psychologist}', [AdminPsychologistController::class, 'show'])->name('psychologists.show');
    Route::post('psychologists/{psychologist}/verify', [AdminPsychologistController::class, 'verify'])->name('psychologists.verify');
    Route::post('psychologists/{psychologist}/reject', [AdminPsychologistController::class, 'reject'])->name('psychologists.reject');
    Route::delete('psychologists/{psychologist}', [AdminPsychologistController::class, 'destroy'])->name('psychologists.destroy');
    
    Route::get('appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointment-list', [AdminAppointmentController::class, 'index'])->name('appointment-list'); // Alias for backward compatibility
    Route::get('appointments/{appointment}', [AdminAppointmentController::class, 'show'])->name('appointments.show');
    
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    Route::post('payments/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('payments.verify');
    Route::post('payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');
    Route::get('payments/{payment}/receipt', [AdminPaymentController::class, 'downloadReceipt'])->name('payments.receipt');
    
    // Dispute routes
    Route::post('payments/{payment}/dispute', [AdminPaymentController::class, 'dispute'])->name('payments.dispute');
    Route::post('payments/{payment}/resolve-dispute', [AdminPaymentController::class, 'resolveDispute'])->name('payments.resolve-dispute');
    
    // Refund routes
    Route::post('payments/{payment}/request-refund', [AdminPaymentController::class, 'requestRefund'])->name('payments.request-refund');
    Route::post('payments/{payment}/approve-refund', [AdminPaymentController::class, 'approveRefund'])->name('payments.approve-refund');
    Route::post('payments/{payment}/reject-refund', [AdminPaymentController::class, 'rejectRefund'])->name('payments.reject-refund');
    Route::post('payments/{payment}/process-refund', [AdminPaymentController::class, 'processRefund'])->name('payments.process-refund');
    
    Route::get('feedbacks', [AdminFeedbackController::class, 'index'])->name('feedbacks.index');
    Route::get('feedbacks/{feedback}', [AdminFeedbackController::class, 'show'])->name('feedbacks.show');
    Route::delete('feedbacks/{feedback}', [AdminFeedbackController::class, 'destroy'])->name('feedbacks.destroy');
    
    Route::get('reviews', [AdminFeedbackController::class, 'index'])->name('reviews'); // Alias for feedbacks
    Route::get('transactions-list', [AdminPaymentController::class, 'index'])->name('transactions-list'); // Alias for payments
    Route::get('invoice-report', [AdminReportController::class, 'invoiceReport'])->name('invoice-report');
    
    // Notification and Communication routes
    Route::resource('notifications', AdminNotificationController::class)->parameters(['notifications' => 'announcement']);
    Route::post('notifications/{announcement}/send', [AdminNotificationController::class, 'send'])->name('notifications.send');
    Route::post('notifications/{announcement}/toggle-status', [AdminNotificationController::class, 'toggleStatus'])->name('notifications.toggle-status');
    Route::get('profile', [AdminController::class, 'profile'])->name('profile');
    Route::post('profile', [AdminController::class, 'updateProfile'])->name('profile.update');
    Route::post('profile/password', [AdminController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('settings', [AdminController::class, 'settings'])->name('settings');
    Route::get('forgot-password', function() {
        return view('admin.forgot-password');
    })->name('forgot-password');
    Route::get('lock-screen', function() {
        return view('admin.lock-screen');
    })->name('lock-screen');
    Route::get('blank-page', function() {
        return view('admin.blank-page');
    })->name('blank-page');
    
    Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('reports/appointments', [AdminReportController::class, 'appointments'])->name('reports.appointments');
    Route::get('reports/payments', [AdminReportController::class, 'payments'])->name('reports.payments');
    Route::get('reports/users', [AdminReportController::class, 'users'])->name('reports.users');
    Route::get('reports/trends', [AdminReportController::class, 'trends'])->name('reports.trends');

    // Backup and Recovery routes
    Route::resource('backups', AdminBackupController::class)->only(['index', 'store', 'destroy']);
    Route::get('backups/{filename}/download', [AdminBackupController::class, 'download'])->name('backups.download');
    Route::post('backups/{filename}/restore', [AdminBackupController::class, 'restore'])->name('backups.restore');
});

// Legacy routes (keeping for backward compatibility)
Route::get('index', [CustomAuthController::class, 'dashboard']); 
Route::get('login', [CustomAuthController::class, 'index'])->name('login');
Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom'); 
Route::get('register', [CustomAuthController::class, 'registration'])->name('register-user');
Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom'); 
Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');
Route::get('indexPharmacy', [AuthCustomController::class, 'dashboardPharmacy']); 
Route::post('login-custom', [AuthCustomController::class, 'loginCustom'])->name('custom.login'); 
Route::post('registration-custom', [AuthCustomController::class, 'registrationCustom'])->name('custom.register');

Route::get('/', function () {
    return view('index');
})->name('index');
Route::get('/index', function () {
    return view('index');
})->name('index');
Route::get('/index-2', function () {
    return view('index-2');
})->name('index-2');
Route::get('/index-3', function () {
    return view('index-3');
})->name('index-3');
Route::get('/index-4', function () {
    return view('index-4');
})->name('index-4');
Route::get('/index-5', function () {
    return view('index-5');
})->name('index-5');
Route::get('/index-6', function () {
    return view('index-6');
})->name('index-6');
Route::get('/index-7', function () {
    return view('index-7');
})->name('index-7');
Route::get('/index-8', function () {
    return view('index-8');
})->name('index-8');
Route::get('/index-9', function () {
    return view('index-9');
})->name('index-9');
Route::get('/index-10', function () {
    return view('index-10');
})->name('index-10');
Route::get('/index-11', function () {
    return view('index-11');
})->name('index-11');
Route::get('/index-12', function () {
    return view('index-12');
})->name('index-12');
Route::get('/index-13', function () {
    return view('index-13');
})->name('index-13');
Route::get('/index-14', function () {
    return view('index-14');
})->name('index-14');
Route::get('/about-us', function () {
    return view('about-us');
})->name('about-us');
Route::get('/accounts', function () {
    return view('accounts');
})->name('accounts');
Route::get('/add-billing', function () {
    return view('add-billing');
})->name('add-billing');
Route::get('/add-dependent', function () {
    return view('add-dependent');
})->name('add-dependent');
Route::get('/add-prescription', function () {
    return view('add-prescription');
})->name('add-prescription');
Route::get('/appointments', function () {
    return view('appointments');
})->name('appointments');
Route::get('/available-timings', function () {
    return redirect()->route('psychologist.availability.index');
})->name('available-timings');
Route::get('/blank-page', function () {
    return view('blank-page');
})->name('blank-page');
Route::get('/blog-details', function () {
    return view('blog-details');
})->name('blog-details');
Route::get('/blog-grid', function () {
    return view('blog-grid');
})->name('blog-grid');
Route::get('/blog-list', function () {
    return view('blog-list');
})->name('blog-list');
Route::get('/booking-1', function () {
    return view('booking-1');
})->name('booking-1');
Route::get('/booking-2', function () {
    return view('booking-2');
})->name('booking-2');
Route::get('/booking-success-one', function () {
    return view('booking-success-one');
})->name('booking-success-one');
Route::get('/booking-success', function () {
    return view('booking-success');
})->name('booking-success');
Route::get('/booking', function () {
    return view('booking');
})->name('booking');
Route::get('/calendar', function () {
    return view('calendar');
})->name('calendar');
Route::get('/cart', function () {
    return view('cart');
})->name('cart');
Route::get('/change-password', function () {
    return view('change-password');
})->name('change-password');
Route::get('/chat-doctor', function () {
    return view('chat-doctor');
})->name('chat-doctor');
Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');
Route::get('/coming-soon', function () {
    return view('coming-soon');
})->name('coming-soon');
Route::get('/components', function () {
    return view('components');
})->name('components');
Route::get('/consultation', function () {
    return view('consultation');
})->name('consultation');
Route::get('/contact-us', function () {
    return view('contact-us');
})->name('contact-us');
Route::get('/dependent', [App\Http\Controllers\Patient\PatientDependentController::class, 'index'])->name('dependent');
Route::post('/dependent', [App\Http\Controllers\Patient\PatientDependentController::class, 'store'])->name('dependent.store');
Route::put('/dependent/{dependent}', [App\Http\Controllers\Patient\PatientDependentController::class, 'update'])->name('dependent.update');
Route::delete('/dependent/{dependent}', [App\Http\Controllers\Patient\PatientDependentController::class, 'destroy'])->name('dependent.destroy');
Route::post('/dependent/{dependent}/toggle-status', [App\Http\Controllers\Patient\PatientDependentController::class, 'toggleStatus'])->name('dependent.toggle-status');
Route::get('/doctor-add-blog', function () {
    return view('doctor-add-blog');
})->name('doctor-add-blog');
Route::get('/doctor-blog', function () {
    return view('doctor-blog');
})->name('doctor-blog');
Route::get('/doctor-change-password', function () {
    return view('doctor-change-password');
})->name('doctor-change-password');
Route::get('/doctor-dashboard', [PsychologistController::class, 'dashboard'])
    ->middleware(['auth', 'role:psychologist'])
    ->name('doctor-dashboard');
Route::get('/doctor-pending-blog', function () {
    return view('doctor-pending-blog');
})->name('doctor-pending-blog');
Route::get('/doctor-profile-settings', function () {
    return view('doctor-profile-settings');
})->name('doctor-profile-settings');
Route::get('/doctor-profile', function () {
    return view('doctor-profile');
})->name('doctor-profile');
Route::get('/doctor-register-step1', function () {
    return view('doctor-register-step1');
})->name('doctor-register-step1');
Route::get('/doctor-register-step2', function () {
    return view('doctor-register-step2');
})->name('doctor-register-step2');
Route::get('/doctor-register-step3', function () {
    return view('doctor-register-step3');
})->name('doctor-register-step3');
Route::get('/doctor-register', function () {
    return view('doctor-register');
})->name('doctor-register');
Route::get('/doctor-search-grid', function () {
    return view('doctor-search-grid');
})->name('doctor-search-grid');
Route::get('/doctor-signup', function () {
    return view('doctor-signup');
})->name('doctor-signup');
Route::get('/edit-billing', function () {
    return view('edit-billing');
})->name('edit-billing');
Route::get('/edit-blog', function () {
    return view('edit-blog');
})->name('edit-blog');
Route::get('/edit-dependent', function () {
    return view('edit-dependent');
})->name('edit-dependent');
Route::get('/edit-prescription', function () {
    return view('edit-prescription');
})->name('edit-prescription');
Route::get('/email-otp', function () {
    return view('email-otp');
})->name('email-otp');
Route::get('/error-404', function () {
    return view('error-404');
})->name('error-404');
Route::get('/error-500', function () {
    return view('error-500');
})->name('error-500');
Route::get('/faq', function () {
    return view('faq');
})->name('faq');
Route::get('/favourites', function () {
    return view('favourites');
})->name('favourites');
Route::get('/forgot-password', function () {
    return view('forgot-password');
})->name('forgot-password');
Route::get('/forgot-password2', function () {
    return view('forgot-password2');
})->name('forgot-password2');
Route::get('/invoice-view', function () {
    return view('invoice-view');
})->name('invoice-view');
Route::get('/invoices', function () {
    return view('invoices');
})->name('invoices');
Route::get('/login-email-otp', function () {
    return view('login-email-otp');
})->name('login-email-otp');
Route::get('/login-email', function () {
    return view('login-email');
})->name('login-email');
Route::get('/login-phone-otp', function () {
    return view('login-phone-otp');
})->name('login-phone-otp');
Route::get('/login-phone', function () {
    return view('login-phone');
})->name('login-phone');
Route::get('/maintenance', function () {
    return view('maintenance');
})->name('maintenance');
Route::get('/map-grid', function () {
    return view('map-grid');
})->name('map-grid');
Route::get('/map-list', function () {
    return view('map-list');
})->name('map-list');
Route::get('/medical-details', [App\Http\Controllers\Patient\PatientVitalController::class, 'index'])->name('medical-details');
Route::post('/medical-details', [App\Http\Controllers\Patient\PatientVitalController::class, 'store'])->name('medical-details.store');
Route::get('/medical-records', function () {
    return view('medical-records');
})->name('medical-records');
Route::get('/membership-details', function () {
    return view('membership-details');
})->name('membership-details');
Route::get('/mobile-otp', function () {
    return view('mobile-otp');
})->name('mobile-otp');
// Moved to psychologist route group
Route::get('/onboarding-availability', function () {
    return view('onboarding-availability');
})->name('onboarding-availability');
Route::get('/onboarding-consultation', function () {
    return view('onboarding-consultation');
})->name('onboarding-consultation');
Route::get('/onboarding-cost', function () {
    return view('onboarding-cost');
})->name('onboarding-cost');
Route::get('/onboarding-email-otp', function () {
    return view('onboarding-email-otp');
})->name('onboarding-email-otp');
Route::get('/onboarding-email-step-2-verify', function () {
    return view('onboarding-email-step-2-verify');
})->name('onboarding-email-step-2-verify');
Route::get('/onboarding-email', function () {
    return view('onboarding-email');
})->name('onboarding-email');
Route::get('/onboarding-identity', function () {
    return view('onboarding-identity');
})->name('onboarding-identity');
Route::get('/onboarding-lock', function () {
    return view('onboarding-lock');
})->name('onboarding-lock');
Route::get('/onboarding-password', function () {
    return view('onboarding-password');
})->name('onboarding-password');
Route::get('/onboarding-payments', function () {
    return view('onboarding-payments');
})->name('onboarding-payments');
Route::get('/onboarding-personalize', function () {
    return view('onboarding-personalize');
})->name('onboarding-personalize');
Route::get('/onboarding-phone-otp', function () {
    return view('onboarding-phone-otp');
})->name('onboarding-phone-otp');
Route::get('/onboarding-phone', function () {
    return view('onboarding-phone');
})->name('onboarding-phone');
Route::get('/onboarding-preferences', function () {
    return view('onboarding-preferences');
})->name('onboarding-preferences');
Route::get('/onboarding-verification', function () {
    return view('onboarding-verification');
})->name('onboarding-verification');
Route::get('/onboarding-verify-account', function () {
    return view('onboarding-verify-account');
})->name('onboarding-verify-account');
Route::get('/orders-list', function () {
    return view('orders-list');
})->name('orders-list');
Route::get('/patient-details', function () {
    return view('patient-details');
})->name('patient-details');
Route::get('/patient-accounts', function () {
    return view('patient-accounts');
})->name('patient-accounts');
Route::get('/patient-dashboard', function () {
    return view('patient-dashboard');
})->name('patient-dashboard');
Route::get('/patient-dependant-details', function () {
    return view('patient-dependant-details');
})->name('patient-dependant-details');
Route::get('/patient-details', function () {
    return view('patient-details');
})->name('patient-details');
Route::get('/patient-email', function () {
    return view('patient-email');
})->name('patient-email');
Route::get('/patient-family-details', function () {
    return view('patient-family-details');
})->name('patient-family-details');
Route::get('/patient-other-details', function () {
    return view('patient-other-details');
})->name('patient-other-details');
Route::get('/patient-personalize', function () {
    return view('patient-personalize');
})->name('patient-personalize');
Route::get('/patient-profile', function () {
    return view('patient-profile');
})->name('patient-profile');
Route::get('/patient-register-step1', function () {
    return view('patient-register-step1');
})->name('patient-register-step1');
Route::get('/patient-register-step2', function () {
    return view('patient-register-step2');
})->name('patient-register-step2');
Route::get('/patient-register-step3', function () {
    return view('patient-register-step3');
})->name('patient-register-step3');
Route::get('/patient-register-step4', function () {
    return view('patient-register-step4');
})->name('patient-register-step4');
Route::get('/patient-register-step5', function () {
    return view('patient-register-step5');
})->name('patient-register-step5');
Route::get('/patient-signup', function () {
    return view('patient-signup');
})->name('patient-signup');
Route::get('/payment-success', function () {
    return view('payment-success');
})->name('payment-success');
Route::get('/payment', function () {
    return view('payment');
})->name('payment');
Route::get('/pharmacy-details', function () {
    return view('pharmacy-details');
})->name('pharmacy-details');
Route::get('/pharmacy-index', function () {
    return view('pharmacy-index');
})->name('pharmacy-index');
Route::get('/pharmacy-register-step1', function () {
    return view('pharmacy-register-step1');
})->name('pharmacy-register-step1');
Route::get('/pharmacy-register-step2', function () {
    return view('pharmacy-register-step2');
})->name('pharmacy-register-step2');
Route::get('/pharmacy-register-step3', function () {
    return view('pharmacy-register-step3');
})->name('pharmacy-register-step3');
Route::get('/pharmacy-register', function () {
    return view('pharmacy-register');
})->name('pharmacy-register');
Route::get('/pharmacy-search', function () {
    return view('pharmacy-search');
})->name('pharmacy-search');
Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');
Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');
Route::get('/product-all', function () {
    return view('product-all');
})->name('product-all');
Route::get('/product-checkout', function () {
    return view('product-checkout');
})->name('product-checkout');
Route::get('/product-description', function () {
    return view('product-description');
})->name('product-description');
Route::get('/product-healthcare', function () {
    return view('product-healthcare');
})->name('product-healthcare');
Route::get('/profile-settings', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'patient') {
            return redirect()->route('patient.profile');
        } elseif ($user->role === 'psychologist') {
            return redirect()->route('psychologist.profile');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.profile');
        }
    }
    return redirect()->route('login');
})->name('profile-settings');
Route::get('/register', function () {
    return view('register');
})->name('register');
Route::get('/reset-password', function () {
    return view('reset-password');
})->name('reset-password');
Route::get('/reviews', function () {
    return view('reviews');
})->name('reviews');
Route::get('/schedule-timings', function () {
    return view('schedule-timings');
})->name('schedule-timings');
Route::get('/search-2', function () {
    return view('search-2');
})->name('search-2');
Route::get('/search', function () {
    return view('search');
})->name('search');
Route::get('/signup-success', function () {
    return view('signup-success');
})->name('signup-success');
Route::get('/signup', function () {
    return view('signup');
})->name('signup');
Route::get('/social-media', function () {
    return view('social-media');
})->name('social-media');
Route::get('/term-condition', function () {
    return view('term-condition');
})->name('term-condition');
Route::get('/terms-condition', function () {
    return view('terms-condition');
})->name('terms-condition');
Route::get('/video-call', function () {
    return view('video-call');
})->name('video-call');
Route::get('/voice-call', function () {
    return view('voice-call');
})->name('voice-call');
Route::get('/doctor-request', function () {
    return view('doctor-request');
})->name('doctor-request');
Route::get('/doctor-appointment-start', function () {
    return view('doctor-appointment-start');
})->name('doctor-appointment-start');
Route::get('/doctor-upcoming-appointment', function () {
    return view('doctor-upcoming-appointment');
})->name('doctor-upcoming-appointment');
Route::get('/doctor-appointments-grid', function () {
    return view('doctor-appointments-grid');
})->name('doctor-appointments-grid');
Route::get('/doctor-completed-appointment', function () {
    return view('doctor-completed-appointment');
})->name('doctor-completed-appointment');
Route::get('/doctor-specialities', function () {
    return view('doctor-specialities');
})->name('doctor-specialities');   
Route::get('/doctor-payment', function () {
    return view('doctor-payment');
})->name('doctor-payment');    
Route::get('/doctor-appointment-details', function () {
    return view('doctor-appointment-details');
})->name('doctor-appointment-details');  
Route::get('/doctor-awards-settings', function () {
    return view('doctor-awards-settings');
})->name('doctor-awards-settings');    
Route::get('/doctor-business-settings', function () {
    return view('doctor-business-settings');
})->name('doctor-business-settings'); 
Route::get('/doctor-clinics-settings', function () {
    return view('doctor-clinics-settings');
})->name('doctor-clinics-settings');     
Route::get('/doctor-education-settings', function () {
    return view('doctor-education-settings');
})->name('doctor-education-settings');  
Route::get('/doctor-experience-settings', function () {
    return view('doctor-experience-settings');
})->name('doctor-experience-settings');    
Route::get('/doctor-insurance-settings', function () {
    return view('doctor-insurance-settings');
})->name('doctor-insurance-settings');   
Route::get('/doctor-cancelled-appointment', function () {
    return view('doctor-cancelled-appointment');
 })->name('doctor-cancelled-appointment');
Route::get('/patient-appointments', function () {
    return view('patient-appointments');
 })->name('patient-appointments');
 Route::get('/patient-appointment-details', function () {
    return view('patient-appointment-details');
 })->name('patient-appointment-details');
 Route::get('/patient-appointments-grid', function () {
    return view('patient-appointments-grid');
 })->name('patient-appointments-grid');
 Route::get('/patient-cancelled-appointment', function () {
    return view('patient-cancelled-appointment');
 })->name('patient-cancelled-appointment');
 Route::get('/patient-completed-appointment', function () {
    return view('patient-completed-appointment');
 })->name('patient-completed-appointment');
Route::get('/patient-invoices', [App\Http\Controllers\Patient\PatientPaymentController::class, 'index'])->name('patient-invoices');
 Route::get('/patient-upcoming-appointment', function () {
    return view('patient-upcoming-appointment');
 })->name('patient-upcoming-appointment');
 Route::get('/doctor-cancelled-appointment-2', function () {
    return view('doctor-cancelled-appointment-2');
 })->name('doctor-cancelled-appointment-2 ');  
 Route::get('/paitent-details', function () {
    return view('paitent-details');
 })->name('paitent-details'); 
 Route::get('/doctor-profile-2', function () {
    return view('doctor-profile-2');
 })->name('doctor-profile-2');     
 Route::get('/map-list-availability', function () {
    return view('map-list-availability');
 })->name('map-list-availability');                 
 Route::get('/booking-popup', function () {
    return view('booking-popup');
 })->name('booking-popup');                  
 Route::get('/hospitals', function () {
    return view('hospitals');
 })->name('hospitals');
 Route::get('/speciality', function () {
    return view('speciality');
 })->name('speciality');    
 Route::get('/clinic', function () {
    return view('clinic');
 })->name('clinic');    
 Route::get('/delete-account', function () {
    return view('delete-account');
 })->name('delete-account');  
 Route::get('/doctor-grid', function () {
    return view('doctor-grid');       
 })->name('doctor-grid');    
 Route::get('/map-list-availability', function () {
    return view('map-list-availability');
 })->name('map-list-availability');   
   
 Route::get('/two-factor-authentication', function () {
    return view('two-factor-authentication');
 })->name('two-factor-authentication');              
 Route::get('/speciality', function () {
    return view('speciality');
 })->name('speciality');              
                  
           
       


