<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('asset/images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>TTPS - Trainings</title>
    <style>
        :root {
            --primary-color: #009c95;
            --secondary-color: #cc3344;
            --hover-color: #007c77;
            --hover-dark: #006c68;
            --light-bg: #f8f9fa;
            --navbar-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            padding-top: var(--navbar-height);
            color: #333;
        }

        .navbar {
            background-color: var(--primary-color);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1020;
            min-height: var(--navbar-height);
            padding: 0.625rem 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .training-section {
            padding: 2rem 0;
        }

        .filters {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }

        .table-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 1.5rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: var(--light-bg);
            color: #555;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        .table td {
            vertical-align: middle;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fff3e0;
            color: #ef6c00;
        }

        .status-verified {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .status-ongoing {
            background-color: #e3f2fd;
            color: #1565c0;
        }

        .status-completed {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }

        .status-rejected {
            background-color: #ffebee;
            color: #c62828;
        }

        .btn-filter {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-filter:hover {
            background-color: var(--hover-color);
            color: white;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #dee2e6;
            padding: 0.5rem 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 156, 149, 0.25);
        }

        .pagination {
            margin-top: 1.5rem;
            justify-content: center;
        }

        .page-link {
            color: var(--primary-color);
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <a href="#" class="navbar-brand">
                <img src="{{ asset('asset/images/logo.png') }}" alt="Logo" id="logo" style="height: 40px;">
                <span class="portal-name" style="color: white; margin-left: 10px;">Tanzania Teacher Portal</span>
            </a>
            <a href="{{ route('organization.dashboard') }}" class="btn btn-filter">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </nav>

    <div class="container training-section">
        <!-- Filters -->
        <div class="filters">
            <form action="{{ route('organization.trainings') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Search training..." value="{{ $search }}">
                </div>
                <div class="col-md-3">
                    <select class="form-control" name="status">
                        <option value="all">All Status</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ $status == 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="ongoing" {{ $status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="date" class="form-control" name="date_from" placeholder="From" value="{{ $date_from }}">
                        <span class="input-group-text">to</span>
                        <input type="date" class="form-control" name="date_to" placeholder="To" value="{{ $date_to }}">
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-filter w-100">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="table-card">
            @if($trainings->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-folder-open text-muted" style="font-size: 3rem;"></i>
                    <p class="mt-3 text-muted">No trainings found</p>
                </div>
            @else
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <span class="me-2">Show</span>
                        <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href=this.value">
                            @foreach($perPageOptions as $option)
                                <option value="{{ request()->fullUrlWithQuery(['per_page' => $option]) }}" 
                                        {{ $perPage == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                        <span class="ms-2">entries</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Code</th>
                                <th>Education Level</th>
                                <th>Phase</th>
                                <th>Start Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trainings as $training)
                                <tr>
                                    <td>{{ $training->title }}</td>
                                    <td>{{ $training->training_code }}</td>
                                    <td>{{ $training->education_level }}</td>
                                    <td>Phase {{ $training->training_phase }}</td>
                                    <td>{{ date('d M Y', strtotime($training->start_date)) }}</td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower($training->status) }}">
                                            {{ ucfirst($training->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('organization.trainings.show', $training->training_id) }}" class="btn btn-sm btn-info text-white">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="pagination-info">
                        Showing {{ $trainings->firstItem() ?? 0 }} to {{ $trainings->lastItem() ?? 0 }} of {{ $trainings->total() }} entries
                    </div>
                    <div class="datatable-pagination">
                        @if ($trainings->hasPages())
                            <div class="pagination">
                                {{-- Previous Page Link --}}
                                <span class="paginate_button {{ $trainings->onFirstPage() ? 'disabled' : '' }}">
                                    <a href="{{ $trainings->previousPageUrl() }}" {{ $trainings->onFirstPage() ? 'tabindex="-1"' : '' }}>«</a>
                                </span>

                                {{-- Pagination Elements --}}
                                @foreach ($trainings->getUrlRange(1, $trainings->lastPage()) as $page => $url)
                                    <span class="paginate_button {{ $page == $trainings->currentPage() ? 'current' : '' }}">
                                        <a href="{{ $url }}">{{ $page }}</a>
                                    </span>
                                @endforeach

                                {{-- Next Page Link --}}
                                <span class="paginate_button {{ !$trainings->hasMorePages() ? 'disabled' : '' }}">
                                    <a href="{{ $trainings->nextPageUrl() }}" {{ !$trainings->hasMorePages() ? 'tabindex="-1"' : '' }}>»</a>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
