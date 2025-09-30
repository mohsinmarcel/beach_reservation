@extends('tenant.layouts.master')
@section('main-content')
<div class="main w-100 pt-4 pe-4">

                <div class="container">
                    <div class="d-flex justify-content-between mb-4">
                        <h1 class="mb-0">Add New User</h1>
                    </div>
                    <div class="row">
                    <div class="col-6 mb-3">
                        <label for="fname" class="form-label">Name</label>
                        <input type="text" class="form-control" id="fname" name="name">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="user_email" class="form-label ">Email</label>
                        <input type="email" class="form-control" id="user_email" name="email">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="user_phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="user_phone" name="phone">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="user_phone" class="form-label">Select Role</label>
                        <select class="form-select" aria-label="Default select example" name="role_id">
                            @if (isset($roles) && $roles->count() > 0)
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ ucWords($role->name) }}</option>
                                @endforeach
                            @endif
                        </select>
                        <input type="hidden" name="tenant_id" value="session('tenant')['current_user']['tenant_id']">
                    </div>

                    <div class="col-12">
                        <input type="button" class="btn btn-lg btn-primary" id="user_profile_btn" value="Create">
                    </div>
                </div>
                </div>
            </div>
@endsection
