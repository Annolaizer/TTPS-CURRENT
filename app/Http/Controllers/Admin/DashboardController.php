<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $data = $this->getDashboardData();
        return view('admin.dashboard.index', ['dashboardData' => $data]);
    }

    public function apiData()
    {
        return response()->json($this->getDashboardData());
    }

    private function getDashboardData()
    {
        // Get teacher counts by education level
        $teachersByLevel = DB::table('teacher_profiles')
            ->select('education_level', DB::raw('count(*) as total'))
            ->groupBy('education_level')
            ->get()
            ->pluck('total', 'education_level')
            ->toArray();

        // Get teacher counts by education level and gender
        $teachersByLevelAndGender = DB::table('teacher_profiles')
            ->join('personal_info', 'teacher_profiles.user_id', '=', 'personal_info.user_id')
            ->select(
                'education_level',
                'gender',
                DB::raw('count(*) as total')
            )
            ->groupBy('education_level', 'gender')
            ->get();

        // Initialize data structure with the correct keys
        $educationLevels = [
            'Pre Primary' => ['total' => 0, 'unique_teachers' => 0],
            'Primary' => ['total' => 0, 'unique_teachers' => 0],
            'Lower Secondary' => ['total' => 0, 'unique_teachers' => 0],
            'Upper Secondary' => ['total' => 0, 'unique_teachers' => 0]
        ];

        $teachers = [
            'Pre Primary' => ['total' => 0, 'female' => 0, 'male' => 0],
            'Primary' => ['total' => 0, 'female' => 0, 'male' => 0],
            'Lower Secondary' => ['total' => 0, 'female' => 0, 'male' => 0],
            'Upper Secondary' => ['total' => 0, 'female' => 0, 'male' => 0]
        ];

        // Map database education levels to display keys
        $levelMapping = [
            'Pre Primary Education' => 'Pre Primary',
            'Primary Education' => 'Primary',
            'Lower Secondary Education' => 'Lower Secondary',
            'Upper Secondary Education' => 'Upper Secondary'
        ];

        // Fill in the actual data
        foreach ($teachersByLevel as $level => $count) {
            $displayLevel = $levelMapping[$level] ?? $level;
            if (isset($educationLevels[$displayLevel])) {
                $educationLevels[$displayLevel]['total'] = $count;
                $educationLevels[$displayLevel]['unique_teachers'] = $count;
            }
        }

        foreach ($teachersByLevelAndGender as $data) {
            $displayLevel = $levelMapping[$data->education_level] ?? $data->education_level;
            if (isset($teachers[$displayLevel])) {
                $teachers[$displayLevel]['total'] += $data->total;
                if ($data->gender === 'female') {
                    $teachers[$displayLevel]['female'] = $data->total;
                } else {
                    $teachers[$displayLevel]['male'] = $data->total;
                }
            }
        }

        // Get organization statistics
        $organizations = DB::table('organizations')
            ->select(
                DB::raw('count(*) as total'),
                DB::raw('SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as offering_training'),
                DB::raw('SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as not_offering_training')
            )
            ->first();

        $organizationStats = [
            'total' => $organizations->total ?? 0,
            'offering_training' => $organizations->offering_training ?? 0,
            'not_offering_training' => $organizations->not_offering_training ?? 0,
            'offering_training_percentage' => $organizations->total > 0 
                ? round(($organizations->offering_training / $organizations->total) * 100) 
                : 0,
            'not_offering_training_percentage' => $organizations->total > 0 
                ? round(($organizations->not_offering_training / $organizations->total) * 100) 
                : 0
        ];

        // Get user role statistics
        $userRoles = DB::table('users')
            ->select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->get();

        // Format user roles data
        $formattedUserRoles = [
            'teacher' => 0,
            'admin' => 0,
            'organization' => 0,
            'cpd_facilitator' => 0,
            'super_administrator' => 0
        ];

        foreach ($userRoles as $role) {
            $formattedUserRoles[$role->role] = $role->total;
        }

        // Get CPD Facilitator statistics from users table
        $cpdFacilitatorStats = DB::table('users')
            ->where('role', 'cpd_facilitator')
            ->select(
                DB::raw('count(*) as total'),
                DB::raw('SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active'),
                DB::raw('SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending')
            )
            ->first();

        // Get recent activities
        $recentActivities = [];
        $recentTeachers = DB::table('users')
            ->join('personal_info', 'users.user_id', '=', 'personal_info.user_id')
            ->where('role', 'teacher')
            ->orderBy('users.created_at', 'desc')
            ->limit(5)
            ->get(['personal_info.first_name', 'personal_info.last_name', 'users.created_at']);

        foreach ($recentTeachers as $teacher) {
            $recentActivities[] = [
                'type' => 'registration',
                'title' => 'New Teacher Registration',
                'name' => $teacher->first_name . ' ' . $teacher->last_name,
                'time' => Carbon::parse($teacher->created_at)
            ];
        }

        // Prepare chart data
        $chartData = [
            'labels' => ['Pre Primary', 'Primary', 'Lower Secondary', 'Upper Secondary'],
            'datasets' => [
                [
                    'label' => 'Female Teachers',
                    'data' => [
                        $teachers['Pre Primary']['female'] ?? 0,
                        $teachers['Primary']['female'] ?? 0,
                        $teachers['Lower Secondary']['female'] ?? 0,
                        $teachers['Upper Secondary']['female'] ?? 0
                    ],
                    'backgroundColor' => 'rgba(255, 99, 132, 0.8)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1
                ],
                [
                    'label' => 'Male Teachers',
                    'data' => [
                        $teachers['Pre Primary']['male'] ?? 0,
                        $teachers['Primary']['male'] ?? 0,
                        $teachers['Lower Secondary']['male'] ?? 0,
                        $teachers['Upper Secondary']['male'] ?? 0
                    ],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.8)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1
                ]
            ]
        ];

        return [
            'education_levels' => $educationLevels,
            'teachers' => $teachers,
            'organizations' => $organizationStats,
            'user_roles' => $formattedUserRoles,
            'cpd_facilitators' => [
                'total' => $cpdFacilitatorStats->total ?? 0,
                'active' => $cpdFacilitatorStats->active ?? 0,
                'inactive' => $cpdFacilitatorStats->inactive ?? 0,
                'pending' => $cpdFacilitatorStats->pending ?? 0,
                'active_percentage' => $cpdFacilitatorStats->total > 0 
                    ? round(($cpdFacilitatorStats->active / $cpdFacilitatorStats->total) * 100, 1) 
                    : 0
            ],
            'recent_activities' => $recentActivities,
            'chart_data' => $chartData,
            'growth_stats' => [
                'monthly_growth' => 0,
                'yearly_growth' => 0
            ],
            'last_updated' => Carbon::now()->toISOString()
        ];
    }
}
