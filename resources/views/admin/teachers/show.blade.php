@extends('admin.master_layout.index')

@section('content')
<style>
    /* Custom Styling */
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #007bff;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #007bff;
    }

    .table th {
        background-color: #f1f1f1;
        font-weight: 600;
        color: #333;
    }

    .badge {
        font-size: 0.875rem;
        padding: 0.5em 0.75em;
    }

    .btn-primary {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-primary i {
        margin-right: 0.5em;
    }

    h5 {
        color: #007bff;
        font-weight: bold;
        margin-bottom: 1em;
    }

    h4 {
        color: #0056b3;
        font-weight: bold;
        margin-bottom: 1.5em;
    }
</style>

<main class="main-content">
    <div class="container-fluid py-4">

        <!-- Teacher Details Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chalkboard-teacher me-2"></i>Teacher Information</h3>
                <a href="{{ route('admin.teachers.edit', $teacher->teacher_id) }}" class="btn btn-primary btn-sm float-right" style="width: 100px; position: absolute; right: 20px;top: 10px;">
                <i class="fas fa-edit me-2"></i> Edit
            </a>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-info-circle me-2"></i>Personal Information</h5>
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th><i class="fas fa-id-card me-2"></i>Registration Number</th>
                                <td>{{ $teacher->registration_number }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-user me-2"></i>Name</th>
                                <td>{{ $teacher->user->name }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-envelope me-2"></i>Email</th>
                                <td>{{ $teacher->user->email }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-user-check me-2"></i>Status</th>
                                <td>
                                    <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'warning' }}">
                                        <i class="fas fa-circle me-1"></i>{{ ucfirst($teacher->status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-briefcase me-2"></i>Professional Information</h5>
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th><i class="fas fa-graduation-cap me-2"></i>Education Level</th>
                                <td>{{ $teacher->education_level }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-book-open me-2"></i>Teaching Subject</th>
                                <td>{{ $teacher->teaching_subject }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-chart-line me-2"></i>Years of Experience</th>
                                <td>{{ $teacher->years_of_experience }} years</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-school me-2"></i>Current School</th>
                                <td>{{ $teacher->current_school }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Training History Section -->
        <div class="mt-5">
            <h4><i class="fas fa-history me-2"></i>Training History</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th><i class="fas fa-book me-2"></i>Training</th>
                            <th><i class="fas fa-clipboard-check me-2"></i>Status</th>
                            <th><i class="fas fa-calendar-alt me-2"></i>Start Date</th>
                            <th><i class="fas fa-calendar-day me-2"></i>End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teacher->trainings as $training)
                            <tr>
                                <td>{{ $training->title }}</td>
                                <td>
                                    <span class="badge bg-{{ $training->pivot->status === 'completed' ? 'success' : 'info' }}">
                                        <i class="fas fa-circle me-1"></i>{{ ucfirst($training->pivot->status) }}
                                    </span>
                                </td>
                                <td>{{ $training->start_date->format('M d, Y') }}</td>
                                <td>{{ $training->end_date->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No training history found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
