<?php

namespace App\Exports;

use App\Models\Training;
use App\Models\TeacherProfile;
use App\Models\TrainingTeacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class TrainingReportExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Training::select(
            'trainings.title',
            'trainings.description',
            'organizations.name as organization',
            DB::raw('COUNT(DISTINCT CASE WHEN personal_info.gender = "male" THEN training_teachers.id END) as male_participants'),
            DB::raw('COUNT(DISTINCT CASE WHEN personal_info.gender = "female" THEN training_teachers.id END) as female_participants'),
            DB::raw('COUNT(DISTINCT CASE WHEN training_teachers.attendance_status = "attended" THEN training_teachers.id END) as attended'),
            DB::raw('COUNT(DISTINCT CASE WHEN training_teachers.attendance_status != "attended" THEN training_teachers.id END) as not_attended')
        )
        ->leftJoin('organizations', 'trainings.organization_id', '=', 'organizations.organization_id')
        ->leftJoin('training_teachers', 'trainings.training_id', '=', 'training_teachers.training_id')
        ->leftJoin('teacher_profiles', 'training_teachers.teacher_id', '=', 'teacher_profiles.teacher_id')
        ->leftJoin('users', 'teacher_profiles.user_id', '=', 'users.user_id')
        ->leftJoin('personal_info', 'users.user_id', '=', 'personal_info.user_id')
        ->groupBy('trainings.training_id', 'trainings.title', 'trainings.description', 'organizations.name')
        ->get()
        ->map(function ($item) {
            return [
                'Training Title' => $item->title,
                'Description' => $item->description,
                'Organization' => $item->organization,
                'Male Participants' => $item->male_participants,
                'Female Participants' => $item->female_participants,
                'Total Participants' => $item->male_participants + $item->female_participants,
                'Attended' => $item->attended,
                'Not Attended' => $item->not_attended,
                'Attendance Rate' => $item->attended > 0 ? 
                    round(($item->attended / ($item->attended + $item->not_attended)) * 100, 1) . '%' : '0%'
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Training Title',
            'Description',
            'Organization',
            'Male Participants',
            'Female Participants',
            'Total Participants',
            'Attended',
            'Not Attended',
            'Attendance Rate'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A1:I1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4CAF50']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ]
        ];
    }
}
