@extends('tenant.layouts.master')
@section('main-content')
    <div class="main w-100 pt-4 pe-4">
        <div class="container">
            <a href="#" onclick="createNewRole()" class="btn btn-primary">Create New Role</a>
            <div class="d-flex justify-content-between my-4">
                <h1 class="mb-0">All Roles</h1>
                <input type="search" class="form-control w-25" id="userSearch" placeholder="Search user...">
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered w-100" id="userTable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>

                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @if (!empty($roles) && $roles->count() > 0)
                            @foreach ($roles as $index => $user)
                                @if ($user->name === 'owner' )
                                    @continue
                                @endif
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>

                                    <td>{{ ucwords($user->name) ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary" onclick="window.location.href='{{route('tenant.set.permissions',$user->id)}}'">Set Permissions</button>
                                        {{-- <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#editDelete">Delete</button> --}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>

            </div>
        </div>
    </div>
      <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
       function createNewRole() {
    Swal.fire({
        title: 'Create New Role',
        input: 'text',
        inputPlaceholder: 'Enter role name',
        showCancelButton: true,
        confirmButtonText: 'Save',
        cancelButtonText: 'Cancel',
        inputValidator: (value) => {
            if (!value) {
                return 'Role name is required!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            let roleName = result.value;

            // Send AJAX request
            $.ajax({
                url: '{{route('tenant.role.create')}}', // your route URL here
                method: 'POST',
                data: {
                    role_name: roleName,
                    _token: $('meta[name="csrf-token"]').attr('content') // for Laravel CSRF
                },
                success: function (response) {
                    Swal.fire(
                        'Success!',
                        'Role created successfully.',
                        'success'
                    );
                    location.reload();
                    // optionally reload table or append new role
                },
                error: function (xhr) {
                    Swal.fire(
                        'Error!',
                        'Something went wrong. Please try again.',
                        'error'
                    );
                }
            });
        }
    });
}

    </script>
@endsection
