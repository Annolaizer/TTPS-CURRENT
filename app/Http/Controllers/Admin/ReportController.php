<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\User;
use App\Models\Training;
use App\Models\TeacherProfile;
use App\Models\TrainingTeacher;
use Illuminate\Support\Facades\DB;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TrainingReportExport;
use ConsoleTVs\Charts\Facades\Charts;

class ReportController extends Controller
{
    public function index()
    {
        // Teacher Statistics by Education Level
        $teacherStats = TeacherProfile::select(
            DB::raw('COALESCE(education_level, "Not Specified") as education_level'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN training_teachers.status = "active" THEN 1 ELSE 0 END) as active'),
            DB::raw('SUM(CASE WHEN training_teachers.status = "inactive" THEN 1 ELSE 0 END) as inactive')
        )
        ->leftJoin('training_teachers', 'teacher_profiles.teacher_id', '=', 'training_teachers.teacher_id')
        ->groupBy('education_level')
        ->orderBy('education_level')
        ->get();

        // Organization Statistics
        $orgStats = (object)[
            'total' => Organization::count(),
            'active' => Organization::where('status', 'active')->count(),
            'inactive' => Organization::where('status', 'inactive')->count()
        ];

        // User Role Statistics
        $userRoleStats = User::select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')
            ->get();

        // Training Statistics
        $trainingStats = (object)[
            'total_enrollments' => TrainingTeacher::count(),
            'completed' => Training::where('status', 'completed')->count(),
            'in_progress' => Training::where('status', 'ongoing')->count(),
            'not_attended' => Training::whereIn('status', ['pending', 'verified'])->count()
        ];

        // Training Participation by Gender
        $genderStats = TeacherProfile::join('users', 'teacher_profiles.user_id', '=', 'users.user_id')
            ->join('personal_info', 'users.user_id', '=', 'personal_info.user_id')
            ->select(
                'personal_info.gender',
                DB::raw('COUNT(DISTINCT teacher_profiles.teacher_id) as total_teachers'),
                DB::raw('COUNT(DISTINCT training_teachers.id) as total_participants')
            )
            ->leftJoin('training_teachers', 'teacher_profiles.teacher_id', '=', 'training_teachers.teacher_id')
            ->groupBy('personal_info.gender')
            ->get();

        // Training Details with Participation
        $trainingDetails = Training::select(
            'trainings.title',
            'trainings.description',
            'organizations.name as organization',
            DB::raw('COUNT(DISTINCT training_teachers.id) as total_participants'),
            DB::raw('COUNT(DISTINCT CASE WHEN personal_info.gender = "male" THEN training_teachers.id END) as male_participants'),
            DB::raw('COUNT(DISTINCT CASE WHEN personal_info.gender = "female" THEN training_teachers.id END) as female_participants'),
            DB::raw('COUNT(DISTINCT CASE WHEN training_teachers.attendance_status = "attended" THEN training_teachers.id END) as attended'),
            DB::raw('COUNT(DISTINCT CASE WHEN training_teachers.attendance_status = "not_attended" THEN training_teachers.id END) as not_attended'),
            DB::raw('COUNT(DISTINCT CASE WHEN training_teachers.attendance_status = "in_progress" THEN training_teachers.id END) as in_progress'),
            DB::raw('ROUND(COUNT(DISTINCT CASE WHEN training_teachers.attendance_status = "attended" THEN training_teachers.id END) * 100.0 / 
                    NULLIF(COUNT(DISTINCT training_teachers.id), 0), 1) as attendance_rate')
        )
        ->leftJoin('organizations', 'trainings.organization_id', '=', 'organizations.organization_id')
        ->leftJoin('training_teachers', 'trainings.training_id', '=', 'training_teachers.training_id')
        ->leftJoin('teacher_profiles', 'training_teachers.teacher_id', '=', 'teacher_profiles.teacher_id')
        ->leftJoin('users', 'teacher_profiles.user_id', '=', 'users.user_id')
        ->leftJoin('personal_info', 'users.user_id', '=', 'personal_info.user_id')
        ->groupBy('trainings.training_id', 'trainings.title', 'trainings.description', 'organizations.name')
        ->get();

        return view('admin.reports.index', compact(
            'teacherStats',
            'orgStats',
            'userRoleStats',
            'trainingStats',
            'genderStats',
            'trainingDetails'
        ));
    }

    public function generatePDF()
    {
        // Get gender distribution from teacher profiles through personal_info table
        $genderStats = DB::table('teacher_profiles')
            ->join('users', 'teacher_profiles.user_id', '=', 'users.user_id')
            ->join('personal_info', 'users.user_id', '=', 'personal_info.user_id')
            ->select(
                'personal_info.gender',
                DB::raw('COUNT(*) as total_teachers'),
                DB::raw('SUM(CASE WHEN EXISTS (
                    SELECT 1 FROM training_teachers 
                    WHERE training_teachers.teacher_id = teacher_profiles.teacher_id
                ) THEN 1 ELSE 0 END) as total_participants')
            )
            ->groupBy('personal_info.gender')
            ->get();

        // Get training details with participation counts
        $trainingDetails = DB::table('trainings')
            ->join('organizations', 'trainings.organization_id', '=', 'organizations.organization_id')
            ->select(
                'trainings.title',
                'organizations.name as organization',
                DB::raw('SUM(CASE WHEN personal_info.gender = "male" THEN 1 ELSE 0 END) as male_participants'),
                DB::raw('SUM(CASE WHEN personal_info.gender = "female" THEN 1 ELSE 0 END) as female_participants'),
                DB::raw('SUM(CASE WHEN training_teachers.attendance_status = "attended" THEN 1 ELSE 0 END) as attended'),
                DB::raw('SUM(CASE WHEN training_teachers.attendance_status = "not_attended" THEN 1 ELSE 0 END) as not_attended')
            )
            ->leftJoin('training_teachers', 'trainings.training_id', '=', 'training_teachers.training_id')
            ->leftJoin('teacher_profiles', 'training_teachers.teacher_id', '=', 'teacher_profiles.teacher_id')
            ->leftJoin('users', 'teacher_profiles.user_id', '=', 'users.user_id')
            ->leftJoin('personal_info', 'users.user_id', '=', 'personal_info.user_id')
            ->groupBy('trainings.training_id', 'trainings.title', 'organizations.name')
            ->get();

        // Calculate total metrics
        $totalTeachers = $genderStats->sum('total_teachers');
        $totalParticipants = $genderStats->sum('total_participants');
        
        // Calculate gender percentages for pie chart
        $maleStats = $genderStats->where('gender', 'male')->first();
        $femaleStats = $genderStats->where('gender', 'female')->first();
        $malePercentage = $maleStats ? round(($maleStats->total_teachers / $totalTeachers) * 100, 1) : 0;
        $femalePercentage = $femaleStats ? round(($femaleStats->total_teachers / $totalTeachers) * 100, 1) : 0;

        // Calculate average attendance
        $totalAttended = $trainingDetails->sum('attended');
        $totalAttendees = $trainingDetails->sum(function($training) {
            return $training->attended + $training->not_attended;
        });
        $averageAttendance = $totalAttendees > 0 ? round(($totalAttended / $totalAttendees) * 100, 1) : 0;

        $data = [
            'genderStats' => $genderStats,
            'trainingDetails' => $trainingDetails,
            'totalTeachers' => $totalTeachers,
            'totalParticipants' => $totalParticipants,
            'malePercentage' => $malePercentage,
            'femalePercentage' => $femalePercentage,
            'averageAttendance' => $averageAttendance
        ];

        $pdf = PDF::loadView('admin.reports.pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('training_report.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new TrainingReportExport, 'training_report.xlsx');
    }

    private function getReportData()
    {
        $genderStats = TeacherProfile::join('users', 'teacher_profiles.user_id', '=', 'users.user_id')
            ->join('personal_info', 'users.user_id', '=', 'personal_info.user_id')
            ->select(
                'personal_info.gender',
                DB::raw('COUNT(DISTINCT teacher_profiles.teacher_id) as total_teachers'),
                DB::raw('COUNT(DISTINCT training_teachers.id) as total_participants')
            )
            ->leftJoin('training_teachers', 'teacher_profiles.teacher_id', '=', 'training_teachers.teacher_id')
            ->groupBy('personal_info.gender')
            ->get();

        $trainingDetails = Training::select(
            'trainings.title',
            'trainings.description',
            'organizations.name as organization',
            DB::raw('COUNT(DISTINCT training_teachers.id) as total_participants'),
            DB::raw('COUNT(DISTINCT CASE WHEN personal_info.gender = "male" THEN training_teachers.id END) as male_participants'),
            DB::raw('COUNT(DISTINCT CASE WHEN personal_info.gender = "female" THEN training_teachers.id END) as female_participants'),
            DB::raw('COUNT(DISTINCT CASE WHEN training_teachers.attendance_status = "attended" THEN training_teachers.id END) as attended'),
            DB::raw('COUNT(DISTINCT CASE WHEN training_teachers.attendance_status = "not_attended" THEN training_teachers.id END) as not_attended'),
            DB::raw('COUNT(DISTINCT CASE WHEN training_teachers.attendance_status = "in_progress" THEN training_teachers.id END) as in_progress'),
            DB::raw('ROUND(COUNT(DISTINCT CASE WHEN training_teachers.attendance_status = "attended" THEN training_teachers.id END) * 100.0 / 
                    NULLIF(COUNT(DISTINCT training_teachers.id), 0), 1) as attendance_rate')
        )
        ->leftJoin('organizations', 'trainings.organization_id', '=', 'organizations.organization_id')
        ->leftJoin('training_teachers', 'trainings.training_id', '=', 'training_teachers.training_id')
        ->leftJoin('teacher_profiles', 'training_teachers.teacher_id', '=', 'teacher_profiles.teacher_id')
        ->leftJoin('users', 'teacher_profiles.user_id', '=', 'users.user_id')
        ->leftJoin('personal_info', 'users.user_id', '=', 'personal_info.user_id')
        ->groupBy('trainings.training_id', 'trainings.title', 'trainings.description', 'organizations.name')
        ->get();

        // Calculate chart data
        $chartColors = ['#1a237e', '#2e7d32', '#ff6f00'];
        $genderLabels = $genderStats->pluck('gender')->map(function($gender) {
            return ucfirst($gender);
        })->toArray();
        
        $genderData = $genderStats->pluck('total_teachers')->toArray();
        $participationData = $genderStats->map(function($stat) {
            return $stat->total_teachers > 0 
                ? round(($stat->total_participants / $stat->total_teachers) * 100, 1)
                : 0;
        })->toArray();

        return compact('genderStats', 'trainingDetails', 'chartColors', 'genderLabels', 'genderData', 'participationData');
    }
}
