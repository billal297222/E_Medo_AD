@extends('backend.master')
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/datatable/css/datatables.min.css') }}">
@endpush
@section('title', 'Role Permission')

@section('content')
    <main class="app-content content">
        {{-- <div class="row mb-3">
            <div class="col-md-6 text-start">
                <h2 class="section-title">All Roles</h2>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.role.create') }}" class="btn btn-primary">
                    <span>Add Roles</span>
                </a>
            </div>
        </div> --}}

        <div class="card p-3 border rounded shadow-sm">
            <div class="card-body">
                <div class="card-header">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title">All Roles</h3>
                    </div>
                    <div class="col-md-4 text-end">
                        {{-- <button type='button' style='min-width: 115px;' class='btn btn-danger delete_btn d-none'
                        onclick='multi_delete()'>Bulk Delete</button> --}}
                        <a href="{{ route('admin.role.create') }}" class="btn btn-primary" type="button">
                            <span><i class="bi bi-person me-1"></i> Add Roles</span>

                        </a>
                    </div>
                </div>

            </div>

                <div class="table-responsive p-2">
                    <table id="basic_tables" class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th class="text-start">#</th>
                                <th class="text-center">Name</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $index => $role)
                                <tr>
                                    <td class="text-start">{{ $index + 1 }}</td>
                                    <td class="text-center">{{ $role->name }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.role.show', $role->id) }}" class="btn btn-sm btn-info">
                                            {{-- <a href="{{ route('admin.role.show') }}" class="btn btn-sm btn-primary"> --}}
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        {{-- <a href="" class="btn btn-sm btn-info">
                                            <i class="mdi mdi-pencil"></i>
                                        </a> --}}
                                        <a href="" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this?')"> <i
                                                class="mdi mdi-delete"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No roles found.</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
