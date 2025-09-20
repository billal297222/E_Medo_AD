@extends('backend.master')

@section('title', 'Category Page')


@section('content')
    <main class="app-content content">
        <div class="row">
            {{-- Create / Edit Form --}}
            <div class="col-lg-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="m-0">Category <span id="Categorytitle">Create</span></h4>
                    </div>
                    <div class="card-body">
                        {{-- Create Form --}}
                        <form id="createCategory" action="{{ route('category.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="row mb-2">
                                        <label class="col-3 col-form-label">Name</label>
                                        <div class="col-9">
                                            <input type="text" name="name" class="form-control"
                                                placeholder="Category name..." value="{{ old('name') }}">
                                            <div style="display: none" class="text-danger nameExists">Category name already
                                                exists</div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label class="col-3 col-form-label">Priority</label>
                                        <div class="col-9">
                                            <input type="number" name="priority" class="form-control"
                                                placeholder="Priority..." value="{{ old('priority') }}">
                                            <div style="display: none" class="text-danger priorityExists">This priority is
                                                already taken</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">

                                    <div class="col-12 mb-2 ">
                                        <div class="col-6">
                                            <label class=" col-form-label ">Upload Image</label>
                                        </div>

                                        <div class="col-6">

                                            {{-- <img id="I" class="mb-2" width="80" height="80" src="{{ asset('default.jpg') }}" alt=""><br> --}}
                                            <input type="file" name="image" class="form-control-sm"
                                                placeholder="select an image">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 text-end">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="mdi mdi-plus me-1"></i> Create
                                    </button>

                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>

            {{-- Category List Table --}}
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="m-0">Category List</h4>
                    </div>
                    <div class="card-body">
                        <table id="category-table" class="table table-bordered table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Priority</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($categories as $category)
                                    <tr data-id="{{ $category->id }}">
                                        <td>{{ $category->priority }}</td>
                                        <td><img src="{{ asset('categories/' . $category->image) }}" width="40"
                                                alt=""></td>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            <form action="{{ route('category.status', $category->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm {{ $category->status ? 'btn-success' : 'btn-danger' }}">

                                                    {{ $category->status ? 'Active' : 'Inactive' }}
                                                </button>
                                            </form>

                                        </td>

                                        <td>
                                            <a href="{{ route('category.edit', $category->id) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>



                                            <form action="{{ route('category.status', $category->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm {{ $category->status ? 'btn-success' : 'btn-secondary' }}">
                                                    <i class="mdi {{ $category->status ? 'mdi-check' : 'mdi-close' }}"></i>
                                                </button>
                                            </form>



                                            <a href="{{ route('category.destroy', $category->id) }}"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this?')"><i
                                                    class="mdi mdi-delete"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('script')
    <script src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
@endpush
