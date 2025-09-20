@extends('backend.master')

@section('title', 'View & Update Role')

@section('content')
    <main class="app-content content">

        <div class="">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('admin.role.list') }}" class="btn btn-primary">
                            <i class="ri-arrow-left-line me-1"></i>back
                        </a>
                    </div>

                </div>
                <div class="card shadow">

                    <div class="card-header bg-info text-white text-center">

                        <h4 class="mb-0">Role Details & Update</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.role.update', $role->id) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Role Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $role->name) }}">
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div class="mb-3">
                                <label for="permissions" class="form-label">Permissions</label>
                                <div class="row">
                                    @foreach ($permissions as $permission)
                                        <div class="col-md-4">
                                            <label>
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                    {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}>
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="text-end">

                                <button type="submit" class="btn btn-success">Update Role</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
