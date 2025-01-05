<?php

namespace Database\Seeders;

use App\Models\Training;
use App\Models\Organization;
use App\Models\Subject;
use App\Models\Ward;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class TrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some real IDs from the database
        $organizationIds = Organization::pluck('organization_id')->toArray();
        $subjectIds = Subject::pluck('subject_id')->toArray();
        $wardIds = Ward::with(['district.region'])->get();

        if (empty($organizationIds) || empty($subjectIds) || empty($wardIds)) {
            $this->command->error('Please ensure organizations, subjects, and wards are seeded first.');
            return;
        }

        // Sample training titles
        $trainingTitles = [
            'Teaching Methodology Workshop',
            'Digital Learning Integration',
            'Classroom Management Skills',
            'Student Assessment Techniques',
            'Special Education Needs Training',
            'STEM Education Workshop',
            'Language Teaching Methods',
            'Educational Leadership Program',
            'Curriculum Development Workshop',
            'Inclusive Education Training'
        ];

        // Sample training descriptions
        $descriptions = [
            'Comprehensive workshop focusing on modern teaching methodologies and best practices.',
            'Training program to integrate digital tools and technology in classroom teaching.',
            'Advanced techniques for effective classroom management and student engagement.',
            'Workshop on implementing various assessment methods and evaluation techniques.',
            'Specialized training for handling students with special educational needs.',
            'Hands-on workshop for teaching Science, Technology, Engineering, and Mathematics.',
            'Modern approaches to language teaching and learning.',
            'Leadership development program for educational administrators.',
            'Workshop on curriculum design and implementation strategies.',
            'Training on creating inclusive learning environments for all students.'
        ];

        // Education levels
        $educationLevels = ['Primary Education', 'Lower Secondary Education', 'Higher Secondary Education'];

        // Status options
        $statusOptions = ['draft', 'pending', 'verified', 'ongoing', 'completed'];

        // Create 50 sample trainings
        for ($i = 0; $i < 50; $i++) {
            $titleIndex = $i % count($trainingTitles);
            $startDate = Carbon::now()->addDays(rand(-30, 60));
            
            // Get random ward and its related district and region
            $ward = Arr::random($wardIds->toArray());
            
            // Create training
            $training = Training::create([
                'training_code' => 'TRN' . date('Y') . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'organization_id' => Arr::random($organizationIds),
                'region_id' => $ward['district']['region']['region_id'],
                'district_id' => $ward['district']['district_id'],
                'ward_id' => $ward['ward_id'],
                'venue_name' => 'Training Center ' . ($i + 1),
                'title' => $trainingTitles[$titleIndex],
                'description' => $descriptions[$titleIndex],
                'education_level' => Arr::random($educationLevels),
                'training_phase' => rand(1, 4),
                'duration_days' => rand(1, 5),
                'max_participants' => rand(20, 50),
                'start_date' => $startDate,
                'end_date' => $startDate->copy()->addDays(rand(1, 5)),
                'start_time' => sprintf('%02d:00:00', rand(8, 16)),
                'status' => 'pending',
            ]);

            // Attach 2-4 random subjects
            $training->subjects()->attach(
                Arr::random($subjectIds, rand(2, 4))
            );
        }
    }
}
