@extends('backend.master')

@section('title', 'Active Directory Settings')

@push('style')
<style>
    body {
        background-color: #f7f8fa;
    }

    .ad-card {
        border-radius: 12px;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        padding: 20px;
    }

    .ad-card .card-header {
        background: linear-gradient(90deg, #343a40, #495057);
        color: #fff;
        font-weight: bold;
        text-align: center;
        border-radius: 12px 12px 0 0;
        font-size: 1rem;
        padding: 10px 0;
    }

    .form-label {
        font-weight: 500;
    }

    .btn-submit {
        border-radius: 8px;
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }
</style>
@endpush

@section('content')
<div class="app-content content">
    <div class="row justify-content-center mt-4">
        <div class="col-lg-12">

            <div class="card ad-card">
                <div class="card-header">
                   <h4>Active Directory Settings</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('directory.update') }}" method="POST">
                        @csrf

                        @php
                            $envKeys = [
                                'LDAP_CONNECTION',
                                'LDAP_HOST',
                                'LDAP_USERNAME',
                                'LDAP_PASSWORD',
                                'LDAP_BASE_DN',
                                'LDAP_PORT',
                                'LDAP_SSL',
                                'LDAP_TLS',
                                'LDAP_SASL',
                                'LDAP_TIMEOUT',
                                'LDAP_LOGGING'
                            ];
                        @endphp

                        @foreach($envKeys as $key)
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-form-label">{{ str_replace('_', ' ', $key) }}</label>
                                <div class="col-sm-8">
                                    @php $value = env($key); @endphp
                                    @if(str_contains($key, 'PASSWORD'))
                                        <input type="password" name="{{ $key }}" class="form-control" value="{{ $value }}" placeholder="Enter {{ strtolower(str_replace('_', ' ', $key)) }}">
                                    @elseif(in_array($key, ['LDAP_SSL', 'LDAP_TLS', 'LDAP_SASL', 'LDAP_LOGGING']))
                                        <select name="{{ $key }}" class="form-control" required>
                                            <option value="true" {{ $value == 'true' ? 'selected' : '' }}>True</option>
                                            <option value="false" {{ $value == 'false' ? 'selected' : '' }}>False</option>
                                        </select>
                                    @else
                                        <input type="text" name="{{ $key }}" class="form-control" value="{{ $value }}" required>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary btn-submit">Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
