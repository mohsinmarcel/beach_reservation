@extends('tenant.layouts.master')
@section('main-content')
    <div class="main w-100 pt-4 pe-4">
        <div class="container">
            <div class="d-flex justify-content-between my-4">
                <h1 class="mb-0">Manage Permissions for Role : {{ ucwords($role->name) }}</h1>
            </div>

            <div class="card p-3">
                @if (!empty($permissions) && $permissions->count() > 0)
                    <form id="permissionForm">
                        @foreach ($permissions->groupBy('group') as $groupName => $groupPermissions)
                            <h5 class="mt-3 mb-2">{{ ucwords($groupName) }}</h5>
                            <div class="row mt-3">
                                @foreach ($groupPermissions as $permission)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox"
                                                type="checkbox"
                                                name="permissions[]"
                                                value="{{ $permission->id }}"
                                                id="perm_{{ $permission->id }}"
                                                {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                {{ ucwords($permission->name) }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        <div class="mt-3">
                            <button type="button" class="btn btn-success" id="savePermissions">Save Permissions</button>
                        </div>
                    </form>
                @else
                    <span class="text-muted">No permissions available</span>
                @endif
            </div>
        </div>
    </div>

    {{-- JQuery + SweetAlert --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).on('click', '#savePermissions', function () {
            let permissions = [];

            $('#permissionForm .permission-checkbox:checked').each(function () {
                permissions.push($(this).val());
            });

            $.ajax({
                url: "{{ route('tenant.set.permissions.process') }}",
                method: "POST",
                data: {
                    permissions: permissions,
                    role_id: "{{ $role->id }}",
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    Swal.fire('Success!', 'Permissions updated successfully.', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                },
                error: function () {
                    Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                }
            });
        });
    </script>
@endsection
