<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('asset/images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>TTPS - Profile Setup</title>
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

        .setup-section {
            padding: 2rem 0;
        }

        .setup-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .setup-header {
            background: linear-gradient(135deg, var(--primary-color), var(--hover-color));
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .setup-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: 8px;
            padding: 0.75rem;
            border: 1px solid #dee2e6;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 156, 149, 0.25);
        }

        .btn-save {
            background-color: var(--primary-color);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .btn-save:hover {
            background-color: var(--hover-color);
            color: white;
        }

        .btn-cancel {
            background-color: #6c757d;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .btn-cancel:hover {
            background-color: #5a6268;
            color: white;
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
            <a href="{{ route('organization.profile') }}" class="btn-save">
                <i class="fas fa-arrow-left"></i> Back to Profile
            </a>
        </div>
    </nav>

    <div class="container setup-section">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="setup-card">
                    <div class="setup-header">
                        <h2><i class="fas fa-cog"></i> Profile Settings</h2>
                        <p class="mb-0">Update your organization information</p>
                    </div>
                    <div class="setup-body">
                        <form action="{{ route('organization.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="form-label">Organization Name</label>
                                <input type="text" class="form-control" name="name" value="{{ $organization->name }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Registration Number</label>
                                <input type="text" class="form-control" name="registration_number" value="{{ $organization->registration_number }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Type of Organization</label>
                                <select class="form-control" name="type" required>
                                    <option value="government" {{ $organization->type == 'government' ? 'selected' : '' }}>Government</option>
                                    <option value="private" {{ $organization->type == 'private' ? 'selected' : '' }}>Private</option>
                                    <option value="ngo" {{ $organization->type == 'ngo' ? 'selected' : '' }}>NGO</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" name="phone" value="{{ $organization->phone }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="3">{{ $organization->address }}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Establishment Date</label>
                                <input type="date" class="form-control" name="established_date" value="{{ $organization->established_date }}" required>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('organization.profile') }}" class="btn btn-cancel">Cancel</a>
                                <button type="submit" class="btn btn-save">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
