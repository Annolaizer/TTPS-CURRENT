<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            'Mathematics' => [
                ['name' => 'Basic Numeracy', 'description' => 'Fundamental mathematics skills for primary education'],
                ['name' => 'Advanced Mathematics', 'description' => 'Advanced mathematical concepts for secondary education'],
                ['name' => 'Mathematics Teaching Methods', 'description' => 'Effective strategies for teaching mathematics']
            ],
            'English Language' => [
                ['name' => 'Basic English', 'description' => 'Fundamental English language skills'],
                ['name' => 'English Literature', 'description' => 'Teaching English literature in secondary schools'],
                ['name' => 'English Teaching Methods', 'description' => 'Modern approaches to teaching English']
            ],
            'Kiswahili' => [
                ['name' => 'Basic Kiswahili', 'description' => 'Fundamental Kiswahili language skills'],
                ['name' => 'Kiswahili Literature', 'description' => 'Teaching Kiswahili literature'],
                ['name' => 'Kiswahili Teaching Methods', 'description' => 'Effective methods for teaching Kiswahili']
            ],
            'Science and Technology' => [
                ['name' => 'Basic Science', 'description' => 'Fundamental science concepts for primary education'],
                ['name' => 'Technology Integration', 'description' => 'Integrating technology in classroom teaching'],
                ['name' => 'STEM Education', 'description' => 'Science, Technology, Engineering, and Mathematics education']
            ]
        ];

        DB::beginTransaction();
        try {
            foreach ($programs as $subjectName => $subjectPrograms) {
                $subject = Subject::where('subject_name', $subjectName)->first();
                
                if ($subject) {
                    foreach ($subjectPrograms as $program) {
                        Program::firstOrCreate(
                            [
                                'program_name' => $program['name'],
                                'subject_id' => $subject->subject_id
                            ],
                            [
                                'description' => $program['description'],
                                'created_at' => now(),
                                'updated_at' => now()
                            ]
                        );
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
