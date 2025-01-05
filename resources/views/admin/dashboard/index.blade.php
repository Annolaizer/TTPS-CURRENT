@extends('admin.master_layout.index')

@section('title', 'TTP - Admin Dashboard')

@section('content')
@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/css/admin/admin_index.css') }}">
@endpush
    <div class="content-wrapper">
        <div class="main-content">
            <div class="row">
                <div class="col-12">
                    <!-- Summary Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stat-card bg-gradient-primary">
                                <div class="stat-card-icon">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <div class="stat-card-info">
                                    <p class="stat-card-title">Pre Primary Education</p>
                                    <h2 class="stat-card-number" id="prePrimaryCount">{{ $dashboardData['education_levels']['Pre Primary']['total'] ?? 0 }}</h2>
                                    <p class="stat-card-progress">
                                        <i class="fas fa-arrow-{{ ($dashboardData['growth_stats']['Pre Primary'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                        <span>{{ abs($dashboardData['growth_stats']['Pre Primary'] ?? 0) }}% {{ ($dashboardData['growth_stats']['Pre Primary'] ?? 0) >= 0 ? 'increase' : 'decrease' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-gradient-success">
                                <div class="stat-card-icon">
                                    <i class="fas fa-school"></i>
                                </div>
                                <div class="stat-card-info">
                                    <p class="stat-card-title">Primary Education</p>
                                    <h2 class="stat-card-number" id="primaryCount">{{ $dashboardData['education_levels']['Primary']['total'] ?? 0 }}</h2>
                                    <p class="stat-card-progress">
                                        <i class="fas fa-arrow-{{ ($dashboardData['growth_stats']['Primary'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                        <span>{{ abs($dashboardData['growth_stats']['Primary'] ?? 0) }}% {{ ($dashboardData['growth_stats']['Primary'] ?? 0) >= 0 ? 'increase' : 'decrease' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-gradient-warning">
                                <div class="stat-card-icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div class="stat-card-info">
                                    <p class="stat-card-title">Lower Secondary Education</p>
                                    <h2 class="stat-card-number" id="lowerSecondaryCount">{{ $dashboardData['education_levels']['Lower Secondary']['total'] ?? 0 }}</h2>
                                    <p class="stat-card-progress">
                                        <i class="fas fa-arrow-{{ ($dashboardData['growth_stats']['Lower Secondary'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                        <span>{{ abs($dashboardData['growth_stats']['Lower Secondary'] ?? 0) }}% {{ ($dashboardData['growth_stats']['Lower Secondary'] ?? 0) >= 0 ? 'increase' : 'decrease' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-gradient-info">
                                <div class="stat-card-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-card-info">
                                    <p class="stat-card-title">Upper Secondary Education</p>
                                    <h2 class="stat-card-number" id="upperSecondaryCount">{{ $dashboardData['education_levels']['Upper Secondary']['total'] ?? 0 }}</h2>
                                    <p class="stat-card-progress">
                                        <i class="fas fa-arrow-{{ ($dashboardData['growth_stats']['Upper Secondary'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                        <span>{{ abs($dashboardData['growth_stats']['Upper Secondary'] ?? 0) }}% {{ ($dashboardData['growth_stats']['Upper Secondary'] ?? 0) >= 0 ? 'increase' : 'decrease' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="info-card-header">
                                    <i class="fas fa-clock text-primary"></i>
                                    <h5>Recent Activities</h5>
                                </div>
                                <div class="info-card-body">
                                    @foreach(array_slice($dashboardData['recent_activities'], 0, 5) as $activity)
                                        <div class="activity-item">
                                            <div class="activity-content">
                                                <div class="activity-title">{{ $activity['title'] }}</div>
                                                <small class="text-muted">{{ $activity['name'] }} - {{ $activity['time']->diffForHumans() }}</small>
                                                @if(isset($activity['training']))
                                                    <div class="activity-details">
                                                        <small class="text-muted">Training: {{ $activity['training'] }}</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="info-card-header">
                                    <i class="fas fa-bell text-danger"></i>
                                    <h5>Quick Actions</h5>
                                </div>
                                <div class="info-card-body">
                                    <div class="quick-actions">
                                        <button class="btn btn-primary btn-sm mb-2">
                                            <i class="fas fa-user-plus me-2"></i>Add New Teacher
                                        </button>
                                        <button class="btn btn-success btn-sm mb-2">
                                            <i class="fas fa-building me-2"></i>Add Organization
                                        </button>
                                        <button class="btn btn-info btn-sm mb-2">
                                            <i class="fas fa-download me-2"></i>Download Report
                                        </button>
                                        <button class="btn btn-warning btn-sm mb-2">
                                            <i class="fas fa-cog me-2"></i>System Settings
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="info-card-header">
                                    <i class="fas fa-tasks text-warning"></i>
                                    <h5>Registered Organizations</h5>
                                </div>
                                <div class="info-card-body">
                                    <div class="alert alert-info">
                                    <p class="text-justify"> <span><i class="fas fa-building"></i></span><span style="margin-left: 20px;">{{ $dashboardData['organizations']['total'] }}</span><span style="margin-left: 20px;">Organization(s)</span></p>
                                    </div>
                                    <div class="status-item">
                                        <div class="d-flex justify-content-between mb-2" data-stat="total-organizations">
                                            <span>Total Registered Organizations</span>
                                            <span class="text-success">100%</span>
                                        </div>
                                        <div class="progress mb-3" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: 100%"></div>
                                        </div>
                                    </div>
                                    <div class="status-item">
                                        <div class="d-flex justify-content-between mb-2" data-stat="organizations-training">
                                            <span>Organizations Offering Training</span>
                                            <span class="text-warning">{{ $dashboardData['organizations']['offering_training_percentage'] }}%</span>
                                        </div>
                                        <div class="progress mb-3" style="height: 6px;">
                                            <div class="progress-bar bg-warning" style="width: {{ $dashboardData['organizations']['offering_training_percentage'] }}%"></div>
                                        </div>
                                    </div>
                                    <div class="status-item">
                                        <div class="d-flex justify-content-between mb-2" data-stat="organizations-no-training">
                                            <span>Organizations Not Offering Training</span>
                                            <span class="text-info">{{ $dashboardData['organizations']['not_offering_training_percentage'] }}%</span>
                                        </div>
                                        <div class="progress mb-3" style="height: 6px;">
                                            <div class="progress-bar bg-info" style="width: {{ $dashboardData['organizations']['not_offering_training_percentage'] }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="chart-container">
                                <canvas id="userActivityChart"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="chart-container">
                                <canvas id="userRolesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
    <script>
        window.dashboardData = {!! json_encode($dashboardData, JSON_HEX_TAG) !!};
    </script>
    <script src="{{ asset('asset/js/admin/charts.js') }}"></script>
@endpush
@endsection