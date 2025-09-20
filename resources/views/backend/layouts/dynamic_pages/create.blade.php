@extends('backend.master')
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/datatable/css/datatables.min.css') }}">
@endpush
@section('title', 'Dynamic Page List')
@section('content')
    <div class="app-content content ">
        <div class="card">
            <div class="card-body">
                <form id="editfaq" action="{{ route('dynamicpages.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row mb-2">
                                <label for="inputEmail3" class="col-3 col-form-label">Page Title</label>
                                <div class="col-9">
                                    <textarea name="page_title" class="form-control" id="page_title" placeholder="Page Title..?" cols="5" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="inputEmail3" class="col-3 col-form-label">Page Content</label>
                                <div class="col-9">
                                    <textarea id="page_content" name="page_content" class="ck-editor form-control @error('ans') is-invalid @enderror"
                                        placeholder="Page Content..?"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="text-end">
                                <button type="submit" class="btn btn-sm btn-primary">Create</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
