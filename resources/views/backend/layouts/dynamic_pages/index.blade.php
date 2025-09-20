@extends('backend.master')

@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/datatable/css/datatables.min.css') }}">
@endpush

@section('title', 'Dynamic Page List')

@section('content')
<div class="app-content content">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="card-title">Dynamic Page List</h3>
                </div>
                <div class="col-md-4 text-end">
                    <!-- Bulk Delete button will be inside the form -->
                    <a href="{{ route('dynamicpages.create') }}" class="btn btn-primary">
                       <span><i class="mdi mdi-plus-circle-outline me-1"></i> Add Dynamic Page</span>

                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Bulk Delete Form Start -->
            <form action="{{ route('dynamicpages.bulk-delete') }}" method="POST" id="bulkDeleteForm">
                @csrf
                <div class="table-responsive mt-4 p-4 card-datatable table-responsive pt-0">
                    <table class="table table-hover" id="data-table">
                        <thead>
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>Page Title</th>
                                <th>Page Content</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dynamicpages as $page)
                                <tr data-id="{{ $page->id }}">
                                    <td>
                                        <input type="checkbox" name="ids[]" value="{{ $page->id }}" class="select_data">
                                    </td>
                                    <td>{{ $page->page_title }}</td>
                                    <td>{!! Str::limit(strip_tags($page->page_content), 100) !!}</td>
                                    <td>
                                        <form action="{{ route('dynamicpages.status', $page->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-sm {{ $page->status === 'active' ? 'btn-success' : 'btn-danger' }}">
                                                {{ $page->status === 'active' ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('dynamicpages.edit', $page->id) }}" class="btn btn-sm btn-info">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <a href="{{ route('dynamicpages.destroy', $page->id) }}"
                                           onclick="return confirm('Are you sure you want to delete this?')"
                                           class="btn btn-sm btn-danger">
                                            <i class="mdi mdi-delete"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Bulk Delete Button -->
                <button type="submit" class="btn btn-danger mt-2"
                    onclick="return confirm('Are you sure you want to delete selected pages?')">
                    Bulk Delete
                </button>
            </form>
            <!-- Bulk Delete Form End -->
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('backend/assets/datatable/js/datatables.min.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    $('#data-table').DataTable();

    // Select / deselect all checkboxes
    document.getElementById('select_all').addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.select_data');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // SweetAlert2 Toast notifications for session messages
    @if(session('toast_success'))
        Swal.fire({
            toast: true,
            icon: 'success',
            title: "{{ session('toast_success') }}",
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    @endif

    @if(session('toast_error'))
        Swal.fire({
            toast: true,
            icon: 'error',
            title: "{{ session('toast_error') }}",
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    @endif
});
</script>
@endpush
