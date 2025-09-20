@extends('backend.master')

@section('title', 'Edit Dynamic Page')

@section('content')
<main class="app-content content">
    <div class="container-fluid py-4">
        <div class="row ">
            <div class="col-lg-12">
                <a href="{{ route('dynamicpages.index') }}" class="btn btn-sm btn-light me-3 shadow-sm mb-3">
                    <i class="mdi mdi-arrow-left"></i> Back
                </a>
                <div class="card shadow-lg rounded-4 border-0">
                    <div class="card-header text-white rounded-top-4 d-flex align-items-center" 
                         {{-- style="background: linear-gradient(135deg, #55ccf0 0%, #09aadb 100%);" --}}
                         >
                        <h4 class="mb-0 text-start flex-grow-1">Edit Dynamic Page</h4>
                    </div>

                    <div class="card-body p-4">
                        <form id="editdynamicpages" action="{{ route('dynamicpages.update', $dynamicpages->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3 row align-items-center">
                                <label class="col-2 col-form-label fw-bold text-center">Page Title</label>
                                <div class="col-10">
                                    <textarea name="page_title" id="page_title" class="form-control rounded-3 shadow-sm" rows="2" placeholder="Enter page title">{{ old('page_title', $dynamicpages->page_title ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="mb-3 row align-items-center">
                                <label class="col-2 col-form-label fw-bold text-center">Page Content</label>
                                <div class="col-10">
                                    <textarea id="page_content" name="page_content" class="ck-editor form-control rounded-3 shadow-sm @error('page_content') is-invalid @enderror" rows="6">{{ old('page_content', $dynamicpages->page_content ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary btn-sm px-4 py-2 shadow-sm">
                                    <i class="bi bi-save me-1"></i> Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
