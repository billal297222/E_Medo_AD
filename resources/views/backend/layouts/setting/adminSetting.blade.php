@extends('backend.master')

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
    <style>
        /* Left column full height */
        .left-column {
            display: flex;
            flex-direction: column;
            height: 400px;
            /* or any fixed height that works for your card */
            justify-content: space-between;
        }

        .image-upload-wrapper img {
    width: 100%;       /* fill wrapper width */
    max-width: 300px;  /* limit max width */
    height: auto;      /* keep aspect ratio */
    object-fit: contain; /* scale properly without cutting */
    display: block;    /* remove inline spacing issues */
    margin: 0 auto;    /* center the image */
}


        /* Image itself takes 30% of wrapper */
        .image-upload-wrapper img {
            height: 30%;
            width: auto;
            object-fit: contain;
        }

        .image-hover {
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 500%;
            /* display: flex; */
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.3);
            opacity: 0;
            transition: opacity 0.2s;
            border-radius: 8px;
        }

        .image-upload-wrapper:hover .image-hover {
            opacity: 1;
        }

        .image-hover i {
            color: #fff;
            font-size: 2rem;
        }

        .card-header {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            font-weight: 600;
        }
    </style>
@endpush

@section('title', 'Admin Setting')

@section('content')
    <div class="app-content content">
        <div class="card shadow-sm">
            <div class="card-header">
                <h4 class="mb-0">Admin Panel Settings</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Left Column: Logo & Favicon -->
                        <div class="col-md-4 left-column">
                            <!-- Admin Logo -->
                            <label class="form-label">Admin Logo</label>
                            <div class="image-upload-wrapper" onclick="document.getElementById('adminLogoInput').click()">
                                <img id="adminLogoPreview"
                                    src="{{ !empty($system_settings->admin_logo) ? asset($system_settings->admin_logo) : asset('admin_logo/default_logo.jpeg') }}"
                                    alt="Admin Logo">
                                <div class="image-hover">
                                    <i class="mdi mdi-camera"></i>
                                </div>
                            </div>
                            <input type="file" name="admin_logo" id="adminLogoInput" hidden
                                onchange="previewImage(this, 'adminLogoPreview')">
                            @error('admin_logo')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Uplode new admin logo.</small>

                            <!-- Admin Favicon -->
                            <div class="image-upload-wrapper mt-3"
                                onclick="document.getElementById('adminFaviconInput').click()">
                                <img id="adminFaviconPreview"
                                    src="{{ !empty($system_settings->admin_favicon) ? asset($system_settings->admin_favicon) : asset('admin_favicon/default_favicon.jpeg') }}"
                                    alt="Admin Favicon">
                                <div class="image-hover">
                                    <i class="mdi mdi-camera"></i>
                                </div>
                            </div>
                            <input type="file" name="admin_favicon" id="adminFaviconInput" hidden
                                onchange="previewImage(this, 'adminFaviconPreview')">
                            @error('admin_favicon')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Uplode new admin favicon.</small>
                        </div>

                        <!-- Right Column: Other Fields -->
                        <div class="col-md-8">
                            <!-- Admin Title -->
                            <div class="mb-3">
                                <label class="form-label">Admin Title</label>
                                <input type="text" class="form-control" name="admin_title"
                                    value="{{ old('admin_title', $system_settings->admin_title ?? '') }}"
                                    placeholder="Admin Title">
                                @error('admin_title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Admin Short Title -->
                            <div class="mb-3">
                                <label class="form-label">Admin Short Title</label>
                                <input type="text" class="form-control" name="admin_short_title"
                                    value="{{ old('admin_short_title', $system_settings->admin_short_title ?? '') }}"
                                    placeholder="Admin Short Title">
                                @error('admin_short_title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Copyright Text -->
                            <div class="mb-3">
                                <label class="form-label">Copyright Text</label>
                                <input type="text" class="form-control" name="admin_copyright_text"
                                    value="{{ old('admin_copyright_text', $system_settings->admin_copyright_text ?? '') }}"
                                    placeholder="Copyright Text">
                                @error('admin_copyright_text')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary px-4">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(previewId).src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
