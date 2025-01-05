@extends('layouts.admin')

@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
            line-height: 1;
        }
        .status-attended {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }
        .status-not_attended {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }
        .status-not_started {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }
        .btn-download {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 4px;
            color: #007bff;
            background-color: rgba(0, 123, 255, 0.1);
            border: 1px solid rgba(0, 123, 255, 0.2);
            transition: all 0.2s ease;
        }
        .btn-download:hover {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.15);
            text-decoration: none;
        }
        .text-muted {
            color: #6c757d !important;
            font-size: 0.75rem;
        }
        #participantsTable {
            font-size: 0.875rem;
            border-collapse: separate;
            border-spacing: 0;
        }
        #participantsTable th, 
        #participantsTable td {
            padding: 0.5rem;
            border: none;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }
        #participantsTable thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.03);
        }
        .card {
            border: none;
            box-shadow: 0 0 10px rgba(0,0,0,.03);
            border-radius: 8px;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #f1f1f1;
            padding: 1rem;
        }
        .card-title {
            margin: 0;
            color: #344767;
            font-size: 1.1rem;
            font-weight: 600;
        }
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 0.3rem 0.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Training Assignment</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.trainings.index') }}">Trainings</a></li>
                            <li class="breadcrumb-item active">Assignment</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Training Info -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Training Information</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Training Code:</strong> {{ $training->training_code }}</p>
                                        <p><strong>Title:</strong> {{ $training->title }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Start Date:</strong> {{ $training->start_date->format('d M Y') }}</p>
                                        <p><strong>End Date:</strong> {{ $training->end_date->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Participants Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Training Participants</h3>
                            </div>
                            <div class="card-body">
                                <table id="participantsTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Report</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <!-- DataTables & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    
    <script>
        // Pass training code to JavaScript
        const trainingCode = "{{ $training->training_code }}";
    </script>
    
    <!-- Page specific script -->
    <script src="{{ asset('asset/js/admin/training_assignment.js') }}"></script>
@endsection
