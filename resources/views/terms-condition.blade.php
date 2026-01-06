<?php $page = 'terms-condition'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Terms & Conditions
        @endslot
        @slot('li_1')
            <a href="{{ url('index') }}">Home</a>
        @endslot
        @slot('li_2')
            Terms & Conditions
        @endslot
    @endcomponent
   
    <!-- Page Content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title mb-4">Terms and Conditions</h3>
                            
                            <div class="terms-content">
                                <h5>1. Acceptance of Terms</h5>
                                <p>By accessing and using MindFlow, you accept and agree to be bound by the terms and provision of this agreement.</p>

                                <h5>2. Use License</h5>
                                <p>Permission is granted to temporarily access the materials on MindFlow's website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:</p>
                                <ul>
                                    <li>Modify or copy the materials</li>
                                    <li>Use the materials for any commercial purpose or for any public display</li>
                                    <li>Attempt to reverse engineer any software contained on MindFlow's website</li>
                                    <li>Remove any copyright or other proprietary notations from the materials</li>
                                </ul>

                                <h5>3. Medical Disclaimer</h5>
                                <p>The information provided on MindFlow is for general informational purposes only and is not intended as a substitute for professional medical advice, diagnosis, or treatment. Always seek the advice of your physician or other qualified health provider with any questions you may have regarding a medical condition.</p>

                                <h5>4. Appointments and Cancellations</h5>
                                <p>Appointments can be scheduled through the platform. Cancellations must be made at least 24 hours in advance. Late cancellations or no-shows may be subject to fees as per the psychologist's policy.</p>

                                <h5>5. Payment Terms</h5>
                                <p>All payments must be made in advance of the appointment. Refund policies are subject to the terms agreed upon at the time of booking. Refund requests will be reviewed on a case-by-case basis.</p>

                                <h5>6. Privacy and Confidentiality</h5>
                                <p>We are committed to protecting your privacy and maintaining the confidentiality of your personal and medical information in accordance with applicable privacy laws and regulations.</p>

                                <h5>7. User Responsibilities</h5>
                                <p>Users are responsible for:</p>
                                <ul>
                                    <li>Maintaining the confidentiality of their account credentials</li>
                                    <li>All activities that occur under their account</li>
                                    <li>Providing accurate and truthful information</li>
                                    <li>Complying with all applicable laws and regulations</li>
                                </ul>

                                <h5>8. Limitation of Liability</h5>
                                <p>MindFlow shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use of or inability to use the service.</p>

                                <h5>9. Modifications to Terms</h5>
                                <p>MindFlow reserves the right to revise these terms at any time without notice. By using this website, you are agreeing to be bound by the then current version of these Terms and Conditions.</p>

                                <h5>10. Contact Information</h5>
                                <p>If you have any questions about these Terms and Conditions, please contact us through the platform's contact features.</p>

                                <div class="mt-4">
                                    <p class="text-muted"><small>Last updated: {{ date('F d, Y') }}</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
@endsection

