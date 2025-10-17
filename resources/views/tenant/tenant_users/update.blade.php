@extends('tenant.layouts.master')
@section('main-content')
    <div class="main w-100 pt-4 pe-4">

        <div class="container">
            <div class="d-flex justify-content-between mb-4">
                <h1 class="mb-0">Update User</h1>
            </div>
            <form id="createUserForm" enctype="multipart/form-data">
                @csrf
                <div class="row">
                <div class="col-6 mb-3">
                    <label for="fname" class="form-label">Name</label>
                    <input type="text" class="form-control" id="fname" name="name" value="{{$tenantUser->name}}">
                </div>
                <div class="col-6 mb-3">
                    <label for="user_email" class="form-label ">Email</label>
                    <input type="email" class="form-control" id="user_email" name="email" value="{{$tenantUser->email}}" readonly>
                </div>
                <div class="col-6 mb-3">
                    <label for="user_phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="user_phone" name="phone" value="{{$tenantUser->phone}}">
                </div>
                <div class="col-6 mb-3">
                    <label for="user_phone" class="form-label">Password</label>
                    <input type="tel" class="form-control" id="user_password" name="password" value="">
                </div>
                <div class="col-6 mb-3">
                    <label for="user_phone" class="form-label">Select Role</label>
                    <select class="form-select" aria-label="Default select example" name="role_id">
                        @if (isset($roles) && $roles->count() > 0)
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{$tenantUser->role_id === $role->id ? 'selected' : ''}}>{{ ucWords($role->name) }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-12">
                    <input type="button" class="btn btn-lg btn-primary" id="user_profile_btn" value="Create" onclick="CreateUser()">
                </div>
            </div>
            </form>

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function CreateUser() {
            var form = $('#createUserForm')[0];
            var formData = new FormData(form);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('tenant.user.update',['id' => $tenantUser->id]) }}', // Replace with your login route
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'User Updated Successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '{{ route('tenant.users.list') }}';
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Remove old validation messages
                    $('#createUserForm .text-danger').remove();

                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        // Laravel validation errors (e.g., missing unique_code or login_password)
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function(fieldName, messages) {
                            const input = $('#createUserForm [name="' + fieldName + '"]');
                            if (input.length > 0) {
                                input.after('<small class="text-danger">' + messages[0] + '</small>');
                            }
                        });
                    } else if (xhr.status === 401) {
                        // Invalid credentials or user not found
                        let msg = xhr.responseJSON?.message || 'Invalid login details.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Failed',
                            text: msg
                        });
                    } else {
                        // Other server errors
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: 'Something went wrong. Please try again later.'
                        });
                    }
                }

            });
        }
    </script>
@endsection
