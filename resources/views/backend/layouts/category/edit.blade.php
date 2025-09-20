@extends('backend.master')

@section('title', 'Category Edit')
@section('content')

    <div class="col-lg-12 mb-3">
        <div class="card shadow-sm rounded-3 border-0">
            <div class="card-header text-white rounded-top-4" 
            {{-- style="background: linear-gradient(135deg, #39b6e7, #42b7e6);" --}}
            >
                <h4 class="mb-0 text-start">Edit Category</h4>
            </div>
            <div class="card-body">
                <form id="updateCategory" action="{{ route('category.update', $category->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">
                        <!-- Left Side: Category Info -->
                        <div class="col-lg-8">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Category Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Category name..."
                                    value="{{ old('name', $category->name ?? '') }}">
                                <div style="display:none" class="text-danger nameExists">Category name already exists</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Priority</label>
                                <input type="number" name="priority" class="form-control" placeholder="Priority..."
                                    value="{{ old('priority', $category->priority ?? '') }}">
                                <div style="display:none" class="text-danger priorityExists">This priority is already taken
                                </div>
                            </div>
                        </div>

                        <!-- Right Side: Image Section -->
                        <div class="col-lg-4">
                            <label class="form-label fw-bold d-block">Category Image <span class="text-danger">(55 x
                                    65)</span></label>

                            <div class="d-flex align-items-center gap-3">
                                <!-- Previous Image -->
                                <div class="text-center">
                                    <p class="small text-muted mb-1">Current Image</p>
                                    <img src="{{ asset('categories/' . $category->image) }}" width="100" height="100"
                                        alt="">
                                </div>

                                <!-- Upload New Image -->
                                <div class="text-center">
                                    <p class="small text-muted mb-2">New Image</p>

                                    <!-- Square Box with Preview -->
                                    <label for="imageUpload"
                                        class="d-block border rounded-3 overflow-hidden position-relative"
                                        style="width:120px; height:120px; cursor:pointer;">

                                        <div class="position-absolute top-50 start-50 translate-middle text-white fw-bold"
                                            style="background:rgba(0,0,0,0.4); padding:2px 6px; font-size:12px;">
                                            Upload
                                        </div>
                                    </label>

                                    <!-- Hidden File Input -->
                                    <input type="file" id="imageUpload" name="image" class="d-none"
                                        onchange="previewImage.src = window.URL.createObjectURL(this.files[0])">
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="mdi mdi-content-save-outline me-1"></i> Update Category
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
