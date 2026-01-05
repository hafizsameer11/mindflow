<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Psychologist;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Prescription;
use App\Models\Feedback;
use App\Models\PsychologistAvailability;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User (or get existing)
        $admin = User::firstOrCreate(
            ['email' => 'admin@mindflow.com'],
            [
                'name' => 'Admin User',
                'password' => 'password123', // Will be hashed by the 'hashed' cast
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // Create Sample Psychologists
        $psychologists = [];
        $specializations = ['Career Counseling', 'Relationship Issues', 'Women\'s Issues', 'Self-Esteem Issues'];
        
        for ($i = 1; $i <= 5; $i++) {
            $user = User::firstOrCreate(
                ['email' => "psychologist{$i}@mindflow.com"],
                [
                    'name' => "Dr. Psychologist {$i}",
                    'password' => 'password123', // Will be hashed by the 'hashed' cast
                    'role' => 'psychologist',
                    'phone' => '+123456789' . $i,
                    'status' => 'active',
                ]
            );

            $psychologist = Psychologist::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'specialization' => $specializations[array_rand($specializations)],
                    'experience_years' => rand(2, 15),
                    'consultation_fee' => rand(300, 700),
                    'bio' => "Experienced psychologist specializing in mental health and wellness.",
                    'qualifications' => ['qualification1.pdf', 'qualification2.pdf'],
                    'verification_status' => $i <= 3 ? 'verified' : 'pending',
                    'verified_at' => $i <= 3 ? now() : null,
                ]
            );

            $psychologists[] = $psychologist;

            // Create availability for verified psychologists
            if ($i <= 3) {
                for ($day = 1; $day <= 5; $day++) {
                    PsychologistAvailability::create([
                        'psychologist_id' => $psychologist->id,
                        'day_of_week' => $day,
                        'start_time' => '09:00',
                        'end_time' => '17:00',
                        'is_available' => true,
                    ]);
                }
            }
        }

        // Create Sample Patients
        $patients = [];
        for ($i = 1; $i <= 10; $i++) {
            $user = User::firstOrCreate(
                ['email' => "patient{$i}@mindflow.com"],
                [
                    'name' => "Patient {$i}",
                    'password' => 'password123', // Will be hashed by the 'hashed' cast
                    'role' => 'patient',
                    'phone' => '+123456789' . ($i + 10),
                    'status' => 'active',
                ]
            );

            $patient = Patient::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'medical_history' => 'No significant medical history.',
                    'emergency_contact' => '+1234567890',
                ]
            );

            $patients[] = $patient;
        }

        // Create Sample Appointments
        foreach ($patients as $index => $patient) {
            if ($index < 5 && isset($psychologists[$index % 3])) {
                $psychologist = $psychologists[$index % 3];
                
                $appointment = Appointment::create([
                    'patient_id' => $patient->id,
                    'psychologist_id' => $psychologist->id,
                    'appointment_date' => now()->addDays(rand(1, 30)),
                    'appointment_time' => now()->setTime(rand(9, 16), 0),
                    'duration' => 60,
                    'status' => ['pending', 'confirmed', 'completed'][rand(0, 2)],
                    'consultation_type' => 'video',
                    'meeting_link' => 'meeting-' . \Illuminate\Support\Str::random(32),
                ]);

                // Create payment for some appointments
                if ($appointment->status !== 'pending') {
                    Payment::create([
                        'appointment_id' => $appointment->id,
                        'amount' => $psychologist->consultation_fee,
                        'receipt_file_path' => 'receipts/sample-receipt.pdf',
                        'bank_name' => 'Sample Bank',
                        'transaction_id' => 'TXN' . rand(100000, 999999),
                        'status' => $appointment->status === 'completed' ? 'verified' : 'pending_verification',
                        'uploaded_at' => now()->subDays(rand(1, 5)),
                        'verified_at' => $appointment->status === 'completed' ? now()->subDays(rand(1, 3)) : null,
                        'verified_by' => $appointment->status === 'completed' ? $admin->id : null,
                    ]);
                }

                // Create prescription for completed appointments
                if ($appointment->status === 'completed') {
                    Prescription::firstOrCreate(
                        ['appointment_id' => $appointment->id],
                        [
                            'psychologist_id' => $psychologist->id,
                            'patient_id' => $patient->id,
                            'notes' => 'Follow-up session recommended in 2 weeks.',
                            'therapy_plan' => 'Continue with weekly sessions focusing on cognitive behavioral therapy.',
                        ]
                    );

                    // Create feedback (only if table exists)
                    if (Schema::hasTable('feedbacks')) {
                        Feedback::firstOrCreate(
                            ['appointment_id' => $appointment->id],
                            [
                                'patient_id' => $patient->id,
                                'psychologist_id' => $psychologist->id,
                                'rating' => rand(4, 5),
                                'comment' => 'Great session, very helpful and understanding.',
                            ]
                        );
                    } else {
                        $this->command->warn('Feedback table not found, skipping feedback creation. Run migrations first.');
                    }
                }
            }
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin@mindflow.com / password123');
        $this->command->info('Psychologists: psychologist1-5@mindflow.com / password123');
        $this->command->info('Patients: patient1-10@mindflow.com / password123');
    }
}
