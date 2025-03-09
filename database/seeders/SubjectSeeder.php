<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            'Mathematics',
            'English Language',
            'Kiswahili',
            'Biology',
            'Chemistry',
            'Physics',
            'Geography',
            'History',
            'Civics',
            'Information and Computer Studies',
            'Agriculture',
            'Commerce',
            'Book Keeping',
            'Physical Education',
            'Fine Art',
            'Music',
            'French Language',
            'Arabic Language',
            'Religious Education',
            'Environmental Studies',
            'Social Studies',
            'Science and Technology',
            'Life Skills',
            'Vocational Skills'
        ];

        DB::beginTransaction();
        try {
            foreach ($subjects as $subject) {
                Subject::firstOrCreate(
                    ['subject_name' => $subject],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
