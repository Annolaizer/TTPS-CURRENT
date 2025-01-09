<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Training;
use App\Models\TrainingTeacher;
use App\Models\TrainingFacilitator;
use Illuminate\Support\Facades\Storage;

class TestReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test PDF content
        $testContent = "Test PDF Content for Training Report\n";
        $testContent .= "Generated at: " . now()->format('Y-m-d H:i:s') . "\n";
        $testContent .= "This is a test file for downloading training reports.";
        
        // Save test file for teacher
        $teacherReportPath = 'reports/teacher_test_report.pdf';
        Storage::disk('public')->put($teacherReportPath, $testContent);
        
        // Save test file for facilitator
        $facilitatorReportPath = 'reports/facilitator_test_report.pdf';
        Storage::disk('public')->put($facilitatorReportPath, $testContent);

        // Get training TRN20250003
        $training = Training::where('training_code', 'TRN20250003')->first();
        
        if ($training) {
            // Update first teacher's report
            $teacher = TrainingTeacher::where('training_id', $training->training_id)->first();
            if ($teacher) {
                $teacher->attendance_status = 'attended';
                $teacher->report_file = $teacherReportPath;
                $teacher->save();
                echo "Updated teacher report file\n";
            }
            
            // Update first facilitator's report
            $facilitator = TrainingFacilitator::where('training_id', $training->training_id)->first();
            if ($facilitator) {
                $facilitator->attendance_status = 'attended';
                $facilitator->report_file = $facilitatorReportPath;
                $facilitator->save();
                echo "Updated facilitator report file\n";
            }
        }
    }
}
