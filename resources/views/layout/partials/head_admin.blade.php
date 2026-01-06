@if (
    !Route::is([
        'appointment-list',
        'blog',
        'specialities',
        'doctor-list',
        'patient-list',
        'reviews',
        'transactions-list',
        'settings',
        'invoice-report',
        'profile',
        'login',
        'register',
        'forgot-password',
        'lock-screen',
        'error-404',
        'error-500',
        'blank-page',
        'components',
        'form-basic',
        'form-inputs',
        'form-horizontal',
        'form-vertical',
        'form-mask',
        'form-validation',
        'tables-basic',
        'data-tables',
        'invoice',
        'calendar',
        'blog-details',
        'edit-blog',
        'product-list',
        'pharmacy-list',
    ]))
    <title>MindFlow - Dashboard</title>
@endif
@if (Route::is(['appointment-list']))
    <title>MindFlow - Appointment List Page</title>
@endif
@if (Route::is(['specialities']))
    <title>MindFlow - Specialities Page</title>
@endif
@if (Route::is(['doctor-list']))
    <title>MindFlow - Doctor List Page</title>
@endif
@if (Route::is(['patient-list']))
    <title>MindFlow - Patient List Page</title>
@endif
@if (Route::is(['reviews']))
    <title>MindFlow - Reviews Page</title>
@endif
@if (Route::is(['transactions-list']))
    <title>MindFlow - Transactions List Page</title>
@endif
@if (Route::is(['settings']))
    <title>MindFlow - Settings Page</title>
@endif
@if (Route::is(['invoice-report']))
    <title>MindFlow - Invoice Report Page</title>
@endif
@if (Route::is(['profile']))
    <title>MindFlow - Profile</title>
@endif
@if (Route::is(['login']))
    <title>MindFlow - Login</title>
@endif
@if (Route::is(['register']))
    <title>MindFlow - Register</title>
@endif
@if (Route::is(['forgot-password']))
    <title>MindFlow - Forgot Password</title>
@endif
@if (Route::is(['lock-screen']))
    <title>MindFlow - Lock Screen</title>
@endif
@if (Route::is(['error-404']))
    <title>MindFlow - Error 404</title>
@endif
@if (Route::is(['error-500']))
    <title>MindFlow - Error 500</title>
@endif
@if (Route::is(['blank-page']))
    <title>MindFlow - Blank Page</title>
@endif
@if (Route::is(['components']))
    <title>MindFlow - Components</title>
@endif
@if (Route::is(['form-basic']))
    <title>MindFlow - Basic Inputs</title>
@endif
@if (Route::is(['form-inputs']))
    <title>MindFlow - Form Input Groups</title>
@endif
@if (Route::is(['form-horizontal']))
    <title>MindFlow - Horizontal Form</title>
@endif
@if (Route::is(['form-vertical']))
    <title>MindFlow - Vertical Form</title>
@endif
@if (Route::is(['form-mask']))
    <title>MindFlow - Form Mask</title>
@endif
@if (Route::is(['form-validation']))
    <title>MindFlow - Form Validation</title>
@endif
@if (Route::is(['tables-basic']))
    <title>MindFlow - Tables Basic</title>
@endif
@if (Route::is(['data-tables']))
    <title>MindFlow - Data Tables</title>
@endif
@if (Route::is(['invoice']))
    <title>MindFlow - Invoice</title>
@endif
@if (Route::is(['calendar']))
    <title>MindFlow - Calendar</title>
@endif
@if (Route::is(['blog', 'blog-details']))
    <title>MindFlow - Blog Page</title>
@endif
@if (Route::is(['add-blog']))
    <title>MindFlow - Add Blog Page</title>
@endif
@if (Route::is(['edit-blog']))
    <title>MindFlow - Edit Blog Page</title>
@endif
@if (Route::is(['product-list']))
    <title>MindFlow - Product List Page</title>
@endif
@if (Route::is(['pharmacy-list']))
    <title>MindFlow - Pharmacy List Page</title>
@endif
<!-- Favicon -->
@if (Route::is(['pending-blog']))
    <title>MindFlow - Pending Blog Page</title>
@endif
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}">
<link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ url('assets_admin/css/bootstrap.min.css') }}">

<!-- Fontawesome CSS -->
<link rel="stylesheet" href="{{ url('/assets_admin/plugins/fontawesome/css/fontawesome.min.css') }}">
<link rel="stylesheet" href="{{ url('/assets_admin/plugins/fontawesome/css/all.min.css') }}">

@if (Route::is(['blog', 'blog-details', 'add-blog', 'edit-blog', 'pending-blog']))
    <link rel="stylesheet" href="{{ url('assets_admin/plugins/fontawesome/css/all.min.css') }}">
@endif
<!-- Feathericon CSS -->
<link rel="stylesheet" href="{{ url('assets_admin/css/feathericon.min.css') }}">
<link rel="stylesheet" href="{{ url('assets_admin/plugins/morris/morris.css') }}">
<!-- Select2 CSS -->
<link rel="stylesheet" href="{{ url('assets_admin/css/select2.min.css') }}">
<!-- Datetimepicker CSS -->
<link rel="stylesheet" href="{{ url('assets_admin/css/bootstrap-datetimepicker.min.css') }}">

<!-- Full Calander CSS -->
<link rel="stylesheet" href="{{ url('assets_admin/plugins/fullcalendar/fullcalendar.min.css') }}">

<!-- Datatables CSS -->
<link rel="stylesheet" href="{{ url('assets_admin/plugins/datatables/datatables.min.css') }}">

<!-- Main CSS -->
<link rel="stylesheet" href="{{ url('assets_admin/css/custom.css') }}">
