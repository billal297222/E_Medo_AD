@extends('backend.master')

@section('title', 'Profile & Password')

@push('style')
<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .card-header {
        background: #f8f9fa;
        padding: 15px 20px;
        font-size: 1rem;
        font-weight: 600;
        color: #333;
        border-bottom: 1px solid #e5e5e5;
    }

    .avatar-container {
        position: relative;
        display: inline-block;
        width: 80px;
        height: 80px;
        cursor: pointer;
    }

    .avatar-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #ddd;
    }

    .avatar-hover {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background: rgba(0,0,0,0.3);
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .avatar-container:hover .avatar-hover {
        opacity: 1;
    }

    .form-label {
        font-weight: 500;
    }

    .btn-primary {
        background: #6610f2;
        border-color: #6610f2;
    }

    .btn-primary:hover {
        background: #007bff;
        border-color: #007bff;
    }

    .toast-container {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 1055;
    }

    .form-section {
        margin-bottom: 2rem;
    }
</style>
@endpush

@section('content')
<main class="app-content content py-4">
    <div class="container-fluid">

        {{-- Toast notifications --}}
        <div class="toast-container">
            @if(session('status'))
                <div id="toastProfile" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">{{ session('status') }}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            @if(session('password-status'))
                <div id="toastPassword" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">{{ session('password-status') }}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>

        <div class="card">

            <div class="card-body">

                {{-- Profile Section --}}
                <div class="form-section">
                    <h5 class="mb-3"><i class="mdi mdi-account-circle me-2"></i>Profile Update</h5>
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        {{-- Avatar --}}
                        <div class="mb-3 position-relative d-inline-block" style="width: 100px; height: 100px; cursor: pointer;">
                            <label class="position-relative w-100 h-100 m-0 p-0">
                                <img src="{{ $user->avatar ? asset('uploads/avatar/' . $user->avatar) : asset('images/default-avatar.png') }}"
                                    class="rounded-circle border border-2 w-100 h-100" style="object-fit: cover;" alt="Avatar">
                                <input type="file" name="avatar" id="avatarInput" hidden onchange="previewAvatar(this)">
                                <div class="avatar-hover">
                                    <i class="mdi mdi-camera text-white fs-4"></i>
                                </div>
                            </label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                        </div>

                        <div class="d-flex justify-content-start">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>
<br>
                <hr>
<br>
                {{-- Password Section --}}
                <div class="form-section">
                    <h5 class="mb-3"><i class="mdi mdi-lock-outline me-2"></i>Password Update</h5>
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <div class="d-flex justify-content-start">
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>
</main>

@push('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Avatar click to open file input
    document.querySelectorAll('.mb-3.position-relative img').forEach(img => {
        img.addEventListener('click', function() {
            this.closest('label').querySelector('input[type="file"]').click();
        });
    });

    // Preview avatar before upload
     function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarLabel.querySelector('img').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Hover effect for overlay
    document.querySelectorAll('.mb-3.position-relative label').forEach(label => {
        const overlay = label.querySelector('.avatar-hover');
        label.addEventListener('mouseenter', () => overlay.style.opacity = 1);
        label.addEventListener('mouseleave', () => overlay.style.opacity = 0);
    });

    // Show toast notifications
    document.addEventListener('DOMContentLoaded', function() {
        ['toastProfile','toastPassword'].forEach(id => {
            const el = document.getElementById(id);
            if(el) new bootstrap.Toast(el).show();
        });
    });
</script>
@endpush
@endsection

