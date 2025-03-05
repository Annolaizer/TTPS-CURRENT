<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> TTP - CPD Facilitator Info</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('asset/images/logo.png') }}" type="image/x-icon">
    
    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="asset/css/CPD/cpd.css">
    <link rel="stylesheet" href="asset/css/academic_entry.css">
    <!-- first css -->
    <style>
        /* Variables */
        :root {
            --primary-color: #009c95;
            --secondary-color: #cc3344;
            --hover-color: #007c77;
            --hover-dark: #006c68;
            --light-bg: #f8f8f8;
            --navbar-height: 70px;
            --topbar-height: 40px;
            --container-max-width: 1200px;
        }

        /* Layout */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: var(--light-bg);
            padding-top: calc(var(--navbar-height) + var(--topbar-actual-height, var(--topbar-height)));
            margin: 0;
        }

        .main-wrapper {
            min-height: 100vh;
            padding: 2rem 0;
        }

        .container.w-640 {
            max-width: 660px !important;
            width: 100%;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Form Sections */
        .form-section {
            border-radius: 8px;
            padding: 1.5rem;
            width: calc(100% + 20px);
            margin: 0 -10px;
        }

        .form-title {
            color: #00b5ad;
            margin-bottom: 0.3rem;
        }

        .form-subtitle {
            color: #666;
            margin-bottom: 1rem;
        }

        /* Form Groups and Rows */
        .form-group {
            margin-bottom: 1rem;
            width: 100%;
        }

        .form-group.mb-4 {
            margin-bottom: 1rem !important;
        }

        .row.g-3 {
            margin: 0 -0.5rem;
            width: calc(100% + 1rem);
            margin-bottom: 1rem !important;
        }

        .row.g-3.mb-4 {
            margin-bottom: 1rem !important;
        }

        .row.g-3 > [class*="col-"] {
            padding: 0 0.5rem;
            margin-bottom: 0;
        }

        /* Form Controls */
        .form-floating {
            position: relative;
            transition: all 0.3s ease-in-out;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-floating > .form-control {
            height: calc(3.5rem + 2px);
            padding: 1rem 0.75rem;
        }

        .form-floating > .form-control:focus,
        .form-floating > .form-control:not(:placeholder-shown) {
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
        }

        .form-floating > label {
            position: absolute;
            top: -2px;
            left: 0;
            height: 100%;
            padding: 1rem 0.75rem;
            pointer-events: none;
            transform-origin: 0 0;
            transition: opacity .1s ease-in-out, transform .1s ease-in-out;
            color: #666;
            font-size: 0.9rem;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            transform: scale(.85) translateY(-0.5rem) translateX(0.15rem);
            color: #00b5ad;
            background: #f8f8f8;
            height: auto;
            padding: 0.25rem 0.5rem;
            margin-left: 0.25rem;
        }

        .form-floating > input[type="file"].form-control ~ label {
            transform: scale(.85) translateY(-0.5rem) translateX(0.15rem);
            background: #f8f8f8;
            height: auto;
            padding: 0.25rem 0.5rem;
            margin-left: 0.25rem;
        }

        .form-floating > input[type="date"].form-control ~ label {
            transform: scale(.85) translateY(-0.5rem) translateX(0.15rem);
            background: #f8f8f8;
            height: auto;
            padding: 0.25rem 0.5rem;
            margin-left: 0.25rem;
        }

        .form-check-input[type="checkbox"] {
            border-color: #ddd;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        .form-check-input[type="checkbox"]:checked {
            background-color: #00b5ad;
            border-color: #00b5ad;
        }

        .form-check-input[type="checkbox"]:focus {
            box-shadow: 0 0 0 0.25rem rgba(0, 181, 173, 0.25);
            border-color: #00b5ad;
        }

        .form-check-label {
            color: #444;
            font-size: 0.9rem;
            cursor: pointer;
        }

        #noQualification:checked ~ #qualificationFields {
            display: none;
        }

        /* Qualification Fields Animation */
        #qualificationFields {
            max-height: 200px;
            transform-origin: top;
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 1rem;
        }

        #qualificationFields.hidden {
            max-height: 0;
            transform: translateY(-10px);
            opacity: 0;
            visibility: hidden;
            margin: 0;
            padding: 0;
        }

        /* Individual field animations */
        #qualificationFields > div {
            transform: translateY(0);
            opacity: 1;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transition-delay: 0.1s;
        }

        #qualificationFields.hidden > div {
            transform: translateY(-10px);
            opacity: 0;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 156, 149, 0.1);
        }

        .form-control:disabled,
        .form-control[readonly] {
            background-color: transparent;
            opacity: 0.7;
        }

        .form-label {
            margin-bottom: 0.3rem;
        }

        /* Alert spacing */
        .alert {
            margin-bottom: 1rem;
        }

        /* Select2 adjustments */
        .select2-container {
            width: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: 42px;
            padding: 0.5rem;
        }

        .select2-container--default .select2-selection--single {
            height: 58px;
            padding: 15px 10px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 56px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 24px;
            color: #212529;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary-color);
        }

        .select2-dropdown {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-search__field {
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem;
            padding: 6px 12px !important;
        }

        .select2-search__field:focus {
            border-color: var(--primary-color) !important;
            outline: none;
        }

        /* Ensure the label stays up when Select2 is focused */
        .form-floating .focused {
            opacity: 0.65;
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
        }

        /* Radio and Checkbox groups */
        .title-radio-group,
        .square-radio-group {
            width: 100%;
            display: flex;
            gap: 1.5rem;
            margin: 0.5rem 0;
        }

        .square-radio-group {
            display: flex;
            gap: 1rem;
        }

        .square-radio-group .form-check {
            margin: 0;
        }

        /* Buttons */
        .btn-primary {
            background-color: #00b5ad;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            padding: 12px;
            font-size: 16px;
        }

        .btn-primary:hover {
            background-color: #009c95;
            color: white;
        }

        .btn-secondary {
            background-color: #00000014;
            color: #212529;
            border: none;
            cursor: pointer;
            padding: 12px;
            flex: 1;
            min-width: 120px;
        }

        .btn-secondary:hover {
            background-color: rgba(0, 0, 0, 0.2);
            color: #212529;
        }

        .btn-primary:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        /* Button Layout */
        .row.mb-3 {
            margin-bottom: 1rem !important;
        }

        .d-flex.gap-4 {
            margin-top: 1rem;
        }

        .flex-grow-1 {
            flex: 1;
        }

        /* Top Bar */
        .top_bar {
            width: 100%;
            background-color: var(--secondary-color);
            color: white;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            position: fixed;
            top: 0;
            z-index: 1030;
            min-height: var(--topbar-height);
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1.4;
            text-align: center;
        }

        /* Navbar */
        .navbar {
            background-color: var(--primary-color);
            transition: all 0.3s ease;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            min-height: var(--navbar-height);
            padding: 0.625rem 0;
            margin: 0;
        }

        .navbar .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: white;
            text-decoration: none;
        }

        .portal-name {
            font-size: 1.25rem;
            font-weight: 500;
            color: white;
            margin: 0;
            white-space: nowrap;
        }

        #logo {
            height: 40px;
            width: auto;
        }

        .user-profile {
            position: relative;
            margin-left: auto;
        }

        .profile-trigger {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: white;
            height: 40px;
            width: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 0;
            margin-right: 1rem !important;
        }

        .profile-trigger:hover {
            background-color: var(--hover-color);
            border-color: var(--hover-color);
        }

        .profile-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 2px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 300px;
            display: none;
            z-index: 1000;
        }

        .profile-dropdown.show {
            display: block;
        }

        .profile-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        .profile-header i.fa-user {
            font-size: 3rem;
            color: var(--primary-color);
            background: #f5f5f5;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }

        .profile-name {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 5px;
            color: #333;
        }

        .profile-email {
            color: #666;
            font-size: 0.9rem;
        }

        .profile-menu {
            padding: 10px 0;
        }

        .profile-menu a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .profile-menu a:hover {
            background-color: var(--light-bg);
        }

        .profile-menu i {
            margin-right: 10px;
            width: 20px;
            color: var(--primary-color);
        }

        .divider {
            height: 1px;
            background-color: #eee;
            margin: 10px 0;
        }

        /* Responsive */
        @media (max-width: 767.98px) {
            :root {
                --navbar-height: 60px;
            }

            .container {
                padding: 0 15px;
            }

            .navbar-brand {
                gap: 8px;
            }

            #logo {
                height: 35px;
            }

            .portal-name {
                font-size: 1rem;
            }

            .profile-trigger {
                height: 35px;
                width: 35px;
                margin-right: -20px !important;
            }

            .profile-dropdown {
                width: 260px;
                right: -10px;
            }

            .profile-header {
                padding: 15px;
            }

            .profile-header i.fa-user {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }

            .profile-name {
                font-size: 1rem;
            }

            .profile-email {
                font-size: 0.8rem;
            }

            .profile-menu a {
                padding: 8px 15px;
                font-size: 0.9rem;
            }

            .form-section {
                padding: 15px;
            }

            .form-title {
                font-size: 1.5rem;
            }

            .form-subtitle {
                font-size: 0.9rem;
            }

            .form-group {
                margin-bottom: 15px;
            }

            .form-label {
                font-size: 0.9rem;
            }

            .progress-circle {
                width: 120px;
                height: 120px;
            }

            .progress-circle::before {
                width: 100px;
                height: 100px;
                top: 10px;
                left: 10px;
            }

            .progress-value {
                font-size: 1.75rem;
            }

            .col-md-3 {
                position: static;
                width: 100%;
                height: auto;
                margin-bottom: 20px;
                padding: 15px;
                background-color: white;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                border-radius: 8px;
            }

            .progress-section {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 1rem;
                padding: 1rem;
            }

            .progress-circle {
                width: 120px;
                height: 120px;
                margin: 0 auto;
            }

            .progress-circle::before {
                width: 100px;
                height: 100px;
            }

            .progress-value {
                font-size: 1.75rem;
            }

            .progress-info {
                text-align: center;
                margin-top: 1rem;
            }

            .progress-info h5 {
                font-size: 1.25rem;
                color: #333;
                margin-bottom: 0.5rem;
                font-weight: 600;
            }

            .progress-info p {
                font-size: 1rem;
                color: #666;
                margin: 0;
            }

            .remaining-fields {
                justify-content: center;
                margin-top: 0.5rem;
            }

            .col-md-9 {
                width: 100%;
                padding: 15px;
            }

            .main-content {
                margin-top: 0;
                padding: 15px;
            }

            .form-section {
                padding: 15px;
                margin-top: 0;
            }
        }

        @media (max-width: 575.98px) {
            .top_bar {
                font-size: 0.7rem;
                padding: 0.5rem 15px;
                line-height: 1.3;
            }

            .navbar {
                padding: 0.5rem 0;
            }

            .portal-name {
                font-size: 0.9rem;
            }

            #logo {
                height: 30px;
            }

            .profile-dropdown {
                width: calc(100vw - 30px);
                right: 15px;
            }

            .form-section {
                padding: 10px;
            }

            .form-title {
                font-size: 1.25rem;
            }

            .form-group {
                margin-bottom: 12px;
            }

            .form-label {
                font-size: 0.9rem;
            }

            .btn {
                width: 100%;
                margin-bottom: 10px;
            }

            .progress-circle {
                width: 100px;
                height: 100px;
            }

            .progress-circle::before {
                width: 80px;
                height: 80px;
                top: 10px;
                left: 10px;
            }

            .progress-value {
                font-size: 1.5rem;
            }

            .col-md-3 {
                padding: 10px;
                margin: 10px;
            }

            .progress-section {
                padding: 0.5rem;
            }

            .progress-circle {
                width: 100px;
                height: 100px;
            }

            .progress-circle::before {
                width: 80px;
                height: 80px;
            }

            .progress-value {
                font-size: 1.5rem;
            }

            .progress-info h5 {
                font-size: 0.9rem;
            }

            .remaining-fields {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 375px) {
            .top_bar {
                font-size: 0.65rem;
                padding: 0.5rem 10px;
            }

            .portal-name {
                font-size: 0.8rem;
            }

            #logo {
                height: 25px;
            }

            .profile-trigger {
                height: 30px;
                width: 30px;
            }

            .form-title {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 768px) {
            .top_bar {
                font-size: 0.75rem;
                padding: 0.5rem 20px;
                height: auto;
                text-align: justify;
                width: 100%;
                hyphens: auto;
                word-wrap: break-word;
                position: fixed;
                top: 0;
            }
            .top_bar p {
                margin: 0;
                padding: 0;
            }
            :root {
                --topbar-height: auto;
            }
            body {
                padding-top: calc(var(--navbar-height) + 2.5rem);
            }
            .navbar {
                position: fixed;
                top: var(--topbar-actual-height, 2.5rem);
                transform: none;
                margin: 0;
                transition: top 0.3s ease;
            }
        }

        /* Progress section styles */
        .progress-sidenav {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: 300px;
            height: calc(100vh - var(--navbar-height));
            background-color: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            z-index: 1000;
            overflow-y: auto;
        }

        .progress-section {
            min-height: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 2rem;
            background: white;
        }

        .progress-top {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
        }

        .progress-middle {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
            flex-grow: 1;
            justify-content: center;
        }

        .progress-bottom {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            margin-top: auto;
            padding-top: 2rem;
        }

        .progress-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0;
            text-align: center;
            padding: 1rem 0;
            border-bottom: 2px solid #f0f0f0;
            width: 100%;
        }

        .progress-circle-container {
            position: relative;
            width: 180px;
            height: 180px;
            margin: 1rem 0;
        }

        .progress-circle {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: conic-gradient(
                var(--primary-color) 11%,
                #f0f0f0 11% 100%
            );
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .progress-circle::before {
            content: '';
            position: absolute;
            width: 85%;
            height: 85%;
            background-color: white;
            border-radius: 50%;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
        }

        .progress-circle-inner {
            position: absolute;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .progress-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .progress-text {
            font-size: 1rem;
            color: #666;
            font-weight: 500;
        }

        .progress-info {
            text-align: center;
            margin: 2rem 0;
        }

        .progress-info h5 {
            font-size: 1.25rem;
            color: #333;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .progress-info p {
            font-size: 1rem;
            color: #666;
            margin: 0;
        }

        .remaining-fields {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1rem;
            background-color: #fff5f5;
            border-radius: 8px;
            margin-top: 1.5rem;
        }

        .remaining-fields i {
            color: #dc3545;
            font-size: 1.25rem;
        }

        .remaining-fields span {
            color: #666;
            font-size: 0.95rem;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .progress-sidenav {
                position: relative;
                width: 100%;
                height: auto;
                min-height: 600px;
                margin-bottom: 2rem;
                top: 0;
            }

            .progress-section {
                padding: 1.5rem;
            }

            .progress-middle {
                padding: 2rem 0;
            }
        }

        /* Main Content Styles */
        .main-content {
            padding: 1rem;
            width: 100%;
            margin: 0 auto;
            height: calc(100vh - var(--navbar-height) - var(--topbar-height));
            overflow: hidden;
        }

        /* Custom row styles */
        .custom-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0;
            padding: 0;
            width: 100%;
            gap: 0;
            min-height: calc(100vh - var(--navbar-height) - var(--topbar-height));
            margin-top: calc(var(--navbar-height) + var(--topbar-height));
        }

        .custom-row > [class*="col-"] {
            padding: 0;
            margin: 0;
        }

        /* Ensure existing column styles work with new row */
        .col-md-3, .col-md-9 {
            position: relative;
            width: 100%;
        }

        @media (min-width: 768px) {
            .col-md-3 {
                flex: 0 0 25%;
                max-width: 25%;
            }
            
            .col-md-9 {
                flex: 0 0 75%;
                max-width: 75%;
            }
        }

        /* Layout Structure */
        .col-md-3 {
            background-color: white;
            position: fixed;
            top: calc(var(--navbar-height) + var(--topbar-height));
            left: 0;
            height: calc(100vh - var(--navbar-height) - var(--topbar-height));
            width: 300px; /* Fixed width for Profile Progress */
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .col-md-9 {
            margin-left: 300px; /* Match Profile Progress width */
            padding: 2rem;
            min-height: calc(100vh - var(--navbar-height) - var(--topbar-height));
            background-color: #f8f9fa;
            width: calc(100% - 300px); /* Remaining width */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container.w-640 {
            max-width: 660px !important;
            width: 100%;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .form-section {
            border-radius: 8px;
            padding: 1.5rem;
            width: calc(100% + 20px);
            margin: 0 -10px;
        }

        /* Responsive Layout */
        @media (max-width: 768px) {
            .main-layout {
                flex-direction: column;
            }

            .progress-sidebar {
                width: 100%;
                position: relative;
                top: 0;
                height: auto;
                min-height: 100px;
            }

            .content-area {
                margin-left: 0;
                padding: 1rem;
            }
        }

        /* Side nav styles */
        .progress-sidenav {
            position: fixed;
            top: calc(var(--topbar-actual-height));
            left: 0;
            width: 300px;
            height: calc(100vh - var(--topbar-actual-height));
            background-color: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .progress-section {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            padding: 2rem;
        }

        /* Main content styles */
        .main-content-wrapper {
            margin-left: 300px;
            padding: 2rem;
            min-height: calc(100vh - var(--navbar-height) - var(--topbar-height));
            margin-top: -50px;
            background-color: transparent;
        }

        .form-section {
            padding: 2rem;
            background: transparent;
            box-shadow: none;
            border: none;
        }

        @media (max-width: 768px) {
            .progress-sidenav {
                display: none;
            }

            .main-content-wrapper {
                margin-left: 0;
                padding: 1rem;
                margin-top: calc(var(--navbar-height) + var(--topbar-height) - 20px);
            }

            .form-section {
                padding: 1rem;
            }
        }

        /* Form styling */
        .form-floating {
            position: relative;
            margin-bottom: 0.8rem;
        }

        .form-floating input,
        .form-floating select {
            width: 100%;
            padding: 6px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: transparent;
            height: 38px;
            padding-top: 16px;
            font-size: 0.9rem;
        }

        .form-floating input:focus,
        .form-floating select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 156, 149, 0.1);
        }

        .form-floating label {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.85rem;
            color: #666;
            pointer-events: none;
            transition: 0.2s ease all;
        }

        .form-floating input:focus ~ label,
        .form-floating input:not(:placeholder-shown) ~ label,
        .form-floating select:focus ~ label,
        .form-floating select:not(:placeholder-shown) ~ label {
            top: 1px;
            font-size: 0.75rem;
            color: var(--primary-color);
            background-color: var(--light-bg);
            padding: 0 2px;
            display: inline;
            line-height: 1;
            height: auto;
            margin: 0;
        }

        /* Button size adjustment */
        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        /* Button styling */
        .btn-primary {
            background-color: #00b5ad;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            padding: 12px;
            font-size: 16px;
        }

        .btn-primary:hover {
            background-color: #009c95;
            color: white;
        }

        .btn-primary:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        /* Container width */
        .w-640 {
            max-width: 660px !important;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Checkbox styling */
        .form-check-input[type="checkbox"] {
            border-color: #ddd;
            cursor: pointer;
        }

        .form-check-input[type="checkbox"]:checked {
            background-color: #00b5ad;
            border-color: #00b5ad;
        }

        .form-check-input[type="checkbox"]:focus {
            box-shadow: 0 0 0 0.25rem rgba(0, 181, 173, 0.25);
            border-color: #00b5ad;
        }

        .form-check-input[type="checkbox"]:hover {
            border-color: #00b5ad;
        }

        /* Main Layout */
        .main-layout {
            display: flex;
            min-height: calc(100vh - 40px); /* Subtract top bar height */
        }

        /* Progress Sidebar */
        .progress-sidebar {
            width: 25%;
            background-color: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            position: fixed;
            left: 0;
            top: 40px; /* Top bar height */
            height: calc(100vh - 40px);
            z-index: 10;
        }

        /* Content Area */
        .content-area {
            flex: 1;
            margin-left: 25%;
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 40px);
            background-color: #f8f9fa;
        }

        /* Form Container */
        .container.w-640 {
            max-width: 660px !important;
            width: 100%;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Form Section */
        .form-section {
            border-radius: 8px;
            padding: 1.5rem;
            width: calc(100% + 20px);
            margin: 0 -10px;
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {
            .col-md-3 {
                position: relative;
                height: auto;
                width: 100%;
                top: 0;
            }

            .col-md-9 {
                margin-left: 0;
                width: 100%;
                padding: 1rem;
            }

            .container.w-640 {
                padding: 0 0.5rem;
            }
        }

        /* Added proper margin and styling for the Basic Information section */
        .main-content {
            margin-left: 300px;
            padding: 2rem;
            background-color: #f8f9fa;
            min-height: calc(100vh - var(--navbar-height));
        }

        .basic-info-section {
            margin-top: 1rem;
        }

        .basic-info-title {
            color: var(--primary-color);
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .basic-info-subtitle {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }
    </style>
    <!-- second css file -->
     <style>
                .btn-prime {
            background-color: var(--primary-color);
            color: white;
        }
        .text-prime {
            color: var(--primary-color);
        }
        .icon-container {
            display: flex;
            justify-content: center;
            margin: 2rem 0;
        }
        .icon-container i {
            font-size: 10rem;
            color: #009c95;
        }
        .file-input-wrapper {
            position: relative;
        }
        .file-input-modal-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1050;
        }
        .file-input-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1051;
            background: white;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
        }
        .file-drop-zone {
            border: 2px dashed var(--primary-color);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            background: #f8f9fa;
            cursor: pointer;
        }
        .selected-file-info {
            display: none;
            align-items: center;
            gap: 10px;
            margin-top: 1rem;
            padding: 0.5rem;
            background: #e9ecef;
            border-radius: 4px;
        }

     </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <a href="{{ route('teacher.dashboard') }}" class="navbar-brand">
                <img src="{{ asset('asset/images/logo.png') }}" alt="Logo" class="logo" style="width: 50px;">
                <span class="portal-name">Tanzania Teacher Portal</span>
            </a>
            <div class="user-profile">
                <button class="profile-trigger" onclick="toggleDropdown()" style="margin-right: -40px;">
                    <i class="fas fa-user"></i>
                </button>

                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-header">
                        <i class="fas fa-user"></i>
                        <div class="profile-name">{{ Auth::user()->name }}</div>
                        <div class="profile-email">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="profile-menu">
                    <a href="{{ route('cpd_facilitator.dashboard') }}"><i class="fas fa-home text-prime"></i>Home</a>
                        <a href="{{ route('cpd_facilitator.training') }}"><i class="fas fa-certificate text-prime"></i>Training</a>
                        <a href="{{ route('cpd_facilitator.settings') }}"><i class="fas fa-cog text-prime"></i>Account settings</a>
                        <div class="divider"></div>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt text-prime"></i>Logout
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Side Navigation -->
    <div class="progress-sidenav">
        <div class="progress-section">
            <div class="progress-top">
                <h3 class="progress-title">Profile Progress</h3>
            </div>
            <div class="progress-middle">
                <div class="progress-circle-container">
                    <div class="progress-circle">
                        <div class="progress-circle-inner">
                            <span class="progress-value">11%</span>
                            <span class="progress-text">Completed</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="progress-bottom">
                <div class="progress-info">
                    <h5>Profile Status</h5>
                    <p>Complete your profile to access all features</p>
                </div>
                <div class="remaining-fields">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>8 fields remaining to be filled</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-wrapper d-flex align-items-center justify-content-center min-vh-100">
        <div class="container w-640">
            <!-- First Form -->
            <div class="form-section basic-info-section" id="basicInfoForm">
                <h2 class="basic-info-title form-title">Basic Information</h2>
                <p class="basic-info-subtitle form-subtitle">Let's start with some basic information</p>
                <form id="basicInfoForm">
                    <!-- Title and Names Section -->
                    <div class="form-group mb-4">
                        <label class="form-label">Title</label>
                        <div class="title-radio-group" style="display: flex; flex-wrap: nowrap; gap: 1.5rem; width: 100%; overflow-x: auto; -ms-overflow-style: none; scrollbar-width: none;">
                            <div class="form-check" style="margin-left: 20px;">
                                <input class="form-check-input" type="radio" id="title_dr" name="title" value="Dr">
                                <label class="form-check-label" for="title_dr">Dr.</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="title_mr" name="title" value="Mr">
                                <label class="form-check-label" for="title_mr">Mr.</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="title_mrs" name="title" value="Mrs" style="border-radius: 3px;">
                                <label class="form-check-label" for="title_mrs">Mrs.</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="title_ms" name="title" value="Ms" style="border-radius: 3px;">
                                <label class="form-check-label" for="title_ms">Ms.</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="title_rev" name="title" value="Rev" style="border-radius: 3px;">
                                <label class="form-check-label" for="title_rev">Rev.</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="title_prof" name="title" value="Prof" style="border-radius: 3px;">
                                <label class="form-check-label" for="title_prof">Prof.</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="title_other" name="title" value="Other" style="border-radius: 3px;">
                                <label class="form-check-label" for="title_other">Other</label>
                            </div>
                        </div>
                        <div id="otherTitleInput" class="mt-2" style="display: none;">
                            <input type="text" class="form-control" placeholder="Please specify your title">
                        </div>
                    </div>

                    <!-- Names Section -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="firstName" placeholder="First Name" required>
                                <label for="firstName">First Name</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="lastName" placeholder="Last Name" required>
                                <label for="lastName">Last Name</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="otherNames" placeholder="Other Names">
                                <label for="otherNames">Other Names</label>
                            </div>
                        </div>
                    </div>
                    <!-- Other Fields -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Gender</label>
                                <div class="square-radio-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="sex_male" name="sex" value="male">
                                        <label class="form-check-label" for="sex_male">Male</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="sex_female" name="sex" value="female">
                                        <label class="form-check-label" for="sex_female">Female</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="dob" placeholder="Date of Birth" required>
                                <label for="dob">Date of Birth</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="tel" class="form-control" id="telephone" placeholder="Telephone" required>
                                <label for="telephone">Telephone</label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select type="text" class="form-control" id="region" placeholder="region">
                                    <option value=""> Select Region</option>
                                    <option value="Iringa">Iringa</option>
                                    <option value="Njombe">Njombe</option>
                                </select>
                                <label for="Region">Region</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select type="text" class="form-control" id="district" placeholder="LGAs">
                                    <option value=""> Select LGAs</option>
                                    <option value="Iringa">Njombe</option>
                                    <option value="Njombe">Ramadhani</option>
                                </select>
                                <label for="District">LGAs</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select type="text" class="form-control" id="region" placeholder="Ward">
                                    <option value=""> Select Ward</option>
                                    <option value="Iringa">Njombe</option>
                                    <option value="Ramadhani">Ramadhani</option>
                                </select>
                                <label for="Ward">Ward</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Disability Information -->
                            <label class="form-label">Disability (If any)</label>
                            <div class="disability-checkboxes" style="display: flex; gap: 2rem; flex-wrap: nowrap;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="hearingImpaired">
                                    <label class="form-check-label" for="hearingImpaired">
                                        Hearing impaired
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="speechImpaired">
                                    <label class="form-check-label" for="speechImpaired">
                                        Speech impaired
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="visuallyImpaired">
                                    <label class="form-check-label" for="visuallyImpaired">
                                        Visually impaired
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="otherDisability">
                                    <label class="form-check-label" for="otherDisability">
                                        Other
                                    </label>
                                </div>
                            </div>
                    </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary w-100 mt-2"  id="saveBtn">Save</button>
                    </div>
                </div>
                <div class="d-flex gap-4">
                    <a href="CPD_facilitator_dashboard.html" style="text-decoration: none; color: black;"><button type="button" class="btn btn-secondary flex-grow-1">Back</button></a>
                    <button type="button" class="btn btn-secondary flex-grow-1" id="nextBtn">Next</button>
                </div>
            </form>
        </div>

        <!-- Second Form -->
        <div class="form-section" id="teacherInfoForm" style="display: none;">
            <h2 class="form-title">Professional Information</h2>
            <p class="form-subtitle">Finally, let's get some Professional Information details</p>
            <form class="w-100">
                <!-- Professional Qualification -->
                <div class="form-group mb-4">
                    <label class="form-label">Professional Qualification</label>
                    <select class="form-select" id="professionalQualification" name="professionalQualification" required>
                        <option value="" selected disabled>Please select an option</option>
                        <option value="phd">PhD</option>
                        <option value="msc">MSc</option>
                        <option value="degree">Degree</option>
                        <option value="adv_diploma">Advanced Diploma</option>
                        <option value="diploma">Diploma</option>
                    </select>
                </div>

                <!-- Current Workplace -->
                <div class="form-group mb-4">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="currentWorkplace" name="currentWorkplace" placeholder="Current Workplace" required>
                        <label for="currentWorkplace">Current Workplace</label>
                    </div>
                </div>

                <!-- Working Experience -->
                <div class="form-group mb-4">
                    <div class="form-floating">
                        <input type="number" class="form-control" id="experienceYears" name="experienceYears" placeholder="Years of Experience" required>
                        <label for="experienceYears">Years of Experience</label>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="form-group mb-4">
                    <label class="form-label">Additional Certifications</label>
                    <div class="form-floating">
                        <input type="text" class="form-control" id="additionalCertifications" name="additionalCertifications" placeholder="Additional Certifications">
                        <label for="additionalCertifications">Additional Certifications</label>
                    </div>
                </div>

                <!-- Button Group -->
                <div class="row mb-3">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary w-100" id="professionalInfoSaveBtn">Save</button>
                    </div>
                </div>
                <div class="d-flex gap-4">
                    <button type="button" class="btn btn-secondary flex-grow-1" id="teacherFormBackBtn">Back</button>
                    <button type="button" class="btn btn-secondary flex-grow-1" id="teacherFormNextBtn">Next</button>
                </div>
            </form>
        </div>
        <!-- Academic Entry Section -->
        <div class="form-section" id="academicEntrySection" style="display: none;">
            <h2 class="form-title">Specialization categories</h2>
            <p class="form-subtitle"><i>Next, we'll need entries from your Specialization categories</i></p>
            
            <div class="form-content">
                <!-- Table for displaying entries -->
                <div class="form-group mb-4">
                    <label class="form-label">Specialization categories</label>
                    <select class="form-select" id="professionalQualification" name="professionalQualification" required>
                        <option value="" disabled selected>Select Specialization</option>
                        <option value="data-science">Data Science</option>
                        <option value="web-development">Web Development</option>
                        <option value="cyber-security">Cybersecurity</option>
                        <option value="cloud-computing">Cloud Computing</option>
                        <option value="machine-learning">Machine Learning</option>
                    </select>
                </div>

                <button type="button" class="btn btn-primary mb-4 w-100" data-bs-target="#requestModal">
                    <i class="bi bi-save me-2"></i>Add specialization
                </button>

                <div class="d-flex gap-4">
                    <button type="button" class="btn btn-secondary flex-grow-1" id="academicEntryBackBtn">Back</button>
                    <button type="button" class="btn btn-secondary flex-grow-1" id="academicEntryNextBtn">Next</button>
                </div>
            </div>
        </div>

    </div>
</div>
    <script>
            document.addEventListener('DOMContentLoaded', function() {
        // Function to update topbar height
        function updateTopBarHeight() {
            const topBar = document.querySelector('.top_bar');
            if (topBar) {
                const height = topBar.offsetHeight;
                document.documentElement.style.setProperty('--topbar-actual-height', height + 'px');
                
                // Update navbar position
                const navbar = document.querySelector('.navbar');
                if (navbar) {
                    navbar.style.top = height + 'px';
                }
            }
        }

        // Update on load and resize
        updateTopBarHeight();
        window.addEventListener('resize', updateTopBarHeight);
        // Update when content might change
        setTimeout(updateTopBarHeight, 100);

        // Function to toggle dropdown
        window.toggleDropdown = function() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.profile-trigger') && !e.target.closest('.profile-dropdown')) {
                    dropdown.classList.remove('show');
                }
            });
        }

        // Handle title radio buttons
        document.querySelectorAll('input[name="title"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const otherTitleInput = document.getElementById('otherTitleInput');
                otherTitleInput.style.display = this.value === 'Other' ? 'block' : 'none';
            });
        });

        // Handle other disability checkbox
        document.getElementById('otherDisability').addEventListener('change', function() {
            const otherInput = document.getElementById('otherDisabilityText');
            otherInput.style.display = this.checked ? 'inline-block' : 'none';
            if (this.checked) {
                otherInput.focus();
            }
        });

        // Ensure modal shows up when button is clicked
        $('[data-bs-toggle="modal"]').on('click', function() {
            academicModal.show();
        });
        });

        $(document).ready(function() {
        // last form
            // Add the passport picture form HTML
            const passportFormHTML = `
                <!-- Passport Picture Form -->
                <div class="form-section" id="passportPictureForm" style="display: none;">
                    <h2 class="form-title">Passport Picture</h2>
                    <p class="form-subtitle"><i>Next, you'll need to upload your passport picture</i></p>
                    
                    <div class="form-group mt-4">   
                        
                    </div>

                    <div class="form-group mt-3">
                        <input type="file" class="form-control">
                    </div>
                
                    <div class="form-group mt-3">
                        <div class="d-flex justify-content-between gap-3">
                            <button type="button" class="btn btn-secondary flex-grow-1" id="passportPictureBackBtn">
                                Back
                            </button>
                            <button type="button" class="btn btn-primary flex-grow-1" id="finalize">
                                Finalize
                            </button>
                        </div>
                    </div>
                </div>`;

            // Insert the passport form after the last form
            $('.container.w-640').append(passportFormHTML);

            // Handle next button click on the last form
            $('#teacherFormNextBtn').click(function() {
                $(this).closest('.form-section').hide();
                $('#academicEntrySection').show();
            });

            // end
        $('.select2-nationality').select2({
            placeholder: 'nationality',
            maximumResultsForSearch: 5,
            width: '100%',
            dropdownParent: $('#nationality').parent()
        });
        
        // Adjust the position of the label when Select2 is focused
        $('.select2-nationality').on('select2:open', function() {
            $(this).parent().find('label').addClass('focused');
        }).on('select2:close', function() {
            if (!$(this).val()) {
                $(this).parent().find('label').removeClass('focused');
            }
        });

        // Toggle qualification fields based on checkbox
        $('#noQualification').on('change', function() {
            const $fields = $(this).closest('.form-group').find('#qualificationFields');
            if (this.checked) {
                $fields.slideUp();
                // Clear the fields
                $fields.find('input[type="date"], input[type="file"]').val('');
            } else {
                $fields.slideDown();
            }
        });

        // Set initial state of qualification fields
        if ($('#noQualification').is(':checked')) {
            $('#qualificationFields').hide();
        }

        // Handle form navigation
        $('#nextBtn').on('click', function() {
            $('.form-section').first().fadeOut(300, function() {
                $('#teacherInfoForm').fadeIn(300);
            });
        });

        $('#teacherFormBackBtn').on('click', function() {
            $('#teacherInfoForm').fadeOut(300, function() {
                $('.form-section').first().fadeIn(300);
            });
        });

        // Handle save buttons
        $('#saveBtn, #teacherFormSaveBtn').on('click', function() {
            // Add your save logic here
            alert('Form saved successfully!');
        });

        // Add passport picture preview functionality
        $(document).on('change', '#passportPictureForm input[type="file"]', function(event) {
            const file = event.target.files[0];
            const preview = $(this).closest('.form-section').find('.form-group.mt-4');
            
            if (file) {
                if (!file.type.startsWith('image/')) {
                    alert('Please upload an image file');
                    event.target.value = '';
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    alert('Image size should be less than 2MB');
                    event.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.css({
                        'display': 'flex',
                        'justify-content': 'center',
                        'width': '100%'
                    });
                    preview.html(`
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 10px; width: 200px;">
                            <img src="${e.target.result}" 
                                alt="Passport Preview" 
                                style="width: 100%; height: auto; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: block;">
                            <p style="margin: 0; color: #666; font-size: 0.9rem; text-align: center;">Upload passport picture</p>
                        </div>
                    `);
                };
                reader.readAsDataURL(file);
            }
        });

            // Handle passport form next button
            $('#passportFormNextBtn').click(function() {
                $('#passportPictureForm').hide();
                $('#academicEntrySection').show();
            });

            // Handle academic entry back button
            $('#academicEntryBackBtn').click(function() {
                $('#academicEntrySection').hide();
                $('#teacherInfoForm').show();
            });

            // Handle academic entry next button
            $('#academicEntryNextBtn').click(function() {
                $('#academicEntrySection').hide();
                $('#passportPictureForm').show();
            });

            // Handle back button click on passport form
            $('#passportPictureBackBtn').click(function() {
                $('#passportPictureForm').hide();
                $('#academicEntrySection').show();
            });

            // Initialize Bootstrap modal
            var academicModal = new bootstrap.Modal(document.getElementById('requestModal'), {
                backdrop: 'static',
                keyboard: false
            });
            
            // Handle modal close button
            $('.btn-close, [data-bs-dismiss="modal"]').on('click', function() {
                academicModal.hide();
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            });

            // Handle academic entry form submission
            $('#academicEntryForm').on('submit', function(e) {
                e.preventDefault();
                // Add your form submission logic here
                academicModal.hide();
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            });

            // Ensure modal shows up when button is clicked
            $('[data-bs-toggle="modal"]').on('click', function() {
                academicModal.show();
            });

            // SweetAlert confirmation for finalize button
            $('#finalize').on('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to finalize this process?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#009c95',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, finalize it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire(
                            'Finalized!',
                            'Your process has been completed.',
                            'success'
                        ).then(() => {
                            window.location.href = 'CPD_Trainings.html';
                        });
                    }
                });
            });

        });

    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="asset/js/CPD/cpd.js"></script>
</body>
</html>