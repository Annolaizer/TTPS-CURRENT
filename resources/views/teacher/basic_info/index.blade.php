@extends('teacher.layouts.app')

@section('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<style>
    :root {
        --primary-color: #009c95;
        --bs-danger: #dc3545;
        --bs-success: #28a745;
    }

    .step-wizard {
        display: flex;
        justify-content: space-between;
        margin: 2rem 0;
    }
    
    .step-wizard-item {
        text-align: center;
        position: relative;
        flex: 1;
    }
    
    .step-wizard-item:not(:last-child):after {
        content: '';
        position: absolute;
        top: 20px;
        right: 0;
        width: calc(100% - 40px);
        height: 3px;
        background: #e0e0e0;
        margin: 0 20px;
        transition: 0.3s ease;
        z-index: 1;
    }
    
    .step-wizard-item.active:not(:last-child):after {
        background: var(--primary-color);
        box-shadow: 0 0 10px rgba(0, 156, 149, 0.3);
    }

    .step-wizard-item .step-number {
        width: 40px;
        height: 40px;
        background: #e0e0e0;
        border: 3px solid #fff;
        border-radius: 50%;
        margin: 0 auto 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: bold;
        transition: 0.3s ease;
        position: relative;
        z-index: 2;
    }

    .step-wizard-item.active .step-number {
        background: var(--primary-color);
        transform: scale(1.1);
        box-shadow: 0 3px 8px rgba(0, 156, 149, 0.3);
    }

    .step-wizard-item.completed .step-number {
        background: var(--bs-success);
    }

    .step-wizard-item .step-label {
        margin-top: 8px;
        color: #666;
    }

    .step-wizard-item.active .step-label {
        color: var(--primary-color);
        font-weight: bold;
    }

    .form-step {
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    .form-step.active {
        display: block;
        opacity: 1;
    }

    .btn-navigation {
        margin-top: 2rem;
    }

    .btn {
        border-radius: 6px;
        font-weight: bold;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background-color: #008a84;
        border-color: #008a84;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="step-wizard">
                        <div class="step-wizard-item active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Personal Info</div>
                        </div>
                        <div class="step-wizard-item" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label">Professional Info</div>
                        </div>
                        <div class="step-wizard-item" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Location Info</div>
                        </div>
                    </div>

                    <div class="form-container">
                        <form id="teacherProfileForm" action="{{ route('teacher.profile.update') }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="form-step active" id="step1" data-step="1">
                                @include('teacher.basic_info.partials._personal_info')
                                <div class="btn-navigation d-flex justify-content-end mt-4">
                                    <button type="button" class="btn btn-primary next-step" data-next="2">Next</button>
                                </div>
                            </div>

                            <div class="form-step" id="step2" data-step="2">
                                @include('teacher.basic_info.partials._professional_info')
                                <div class="btn-navigation d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary prev-step" data-prev="1">Previous</button>
                                    <button type="button" class="btn btn-primary next-step" data-next="3">Next</button>
                                </div>
                            </div>

                            <div class="form-step" id="step3" data-step="3">
                                @include('teacher.basic_info.partials._location_info')
                                <div class="btn-navigation d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary prev-step" data-prev="2">Previous</button>
                                    <button type="submit" class="btn btn-success" id="saveProfileBtn">
                                        <span class="normal-text">Save Profile</span>
                                        <span class="loading-text d-none">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Saving...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    const totalSteps = 3;
    let currentStep = 1;
    let isSubmitting = false;

    function initSelect2() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
    }

    function setLoadingState(loading) {
        const btn = $('#saveProfileBtn');
        isSubmitting = loading;
        
        if (loading) {
            btn.prop('disabled', true)
               .find('.normal-text').addClass('d-none');
            btn.find('.loading-text').removeClass('d-none');
        } else {
            btn.prop('disabled', false)
               .find('.normal-text').removeClass('d-none');
            btn.find('.loading-text').addClass('d-none');
        }
    }

    function updateWizard(step) {
        $('.step-wizard-item').removeClass('active completed');
        $('.step-wizard-item').each(function () {
            const stepNum = $(this).data('step');
            if (stepNum < step) $(this).addClass('completed');
            else if (stepNum === step) $(this).addClass('active');
        });
    }

    function navigateToStep(nextStep) {
        $('.form-step').removeClass('active');
        $(`#step${nextStep}`).addClass('active');
        updateWizard(nextStep);
        currentStep = nextStep;
    }

    function validateStep(step) {
        let isValid = true;
        const $step = $(`#step${step}`);
        $step.find('[required]').each(function () {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        return isValid;
    }

    $('.next-step').on('click', function () {
        const nextStep = $(this).data('next');
        if (validateStep(currentStep)) navigateToStep(nextStep);
    });

    $('.prev-step').on('click', function () {
        navigateToStep($(this).data('prev'));
    });

    $('#teacherProfileForm').on('submit', function (e) {
        e.preventDefault();
        if (isSubmitting) return false;

        // Validate all steps
        for (let i = 1; i <= totalSteps; i++) {
            if (!validateStep(i)) {
                navigateToStep(i);
                return false;
            }
        }

        setLoadingState(true);
        const form = $(this);
        const formData = new FormData(this);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                setLoadingState(false);
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 2000
                });
            },
            error: function(xhr) {
                setLoadingState(false);
                let message = 'An error occurred while saving your profile.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: message
                });
            }
        });
    });

    initSelect2();
});
</script>
@stack('scripts')
@endsection