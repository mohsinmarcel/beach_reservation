@extends('tenant.layouts.master')
@section('main-content')
    <div class="main w-100 pt-4 pe-4">
        <div class="container">
            {{-- <a href="{{ route('tenant.users.create') }}" class="btn btn-primary">Add New User</a> --}}
            <div class="d-flex justify-content-between my-4">
                <h1 class="mb-0">All Reservations</h1>
                {{-- <input type="search" class="form-control w-25" id="userSearch" placeholder="Search user..."> --}}
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered w-100" id="userTable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">User Info</th>
                            <th scope="col">Number of Sets</th>
                            <th scope="col">Addon Seats</th>
                            <th scope="col">Addon Umbrellas</th>
                            <th scope="col">Price Applied</th>
                            <th scope="col">Price Total</th>
                            <th scope="col">Booking Date & Time</th>
                            <th scope="col">Room Number</th>
                            <th scope="col" class="text-center">Click To Mark Completed</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @if (!empty($reservations) && $reservations->count() > 0)
                            @foreach ($reservations as $index => $user)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $user->user->name }}<br>{{ $user->user->phone }}</td>
                                    <td>{{ $user->no_of_sets ?? 0 }}</td>
                                    <td>{{ $user->addon_seats ?? 0 }}</td>
                                    <td>{{ $user->addon_umbrellas ?? 0 }}</td>
                                    <td>{{ $user->pricing->name }}</td>
                                    <td>${{ $user->total_price }}</td>
                                    <td>{{ \Carbon\Carbon::parse($user->booking_date)->format('d M Y') }}</td>
                                    <td>{{ $user->room_number ?? 'N/A' }}</td>
                                    {{-- @dd(session('tenant')) --}}

                                    <td>
                                        @if (in_array('tenant.user.reservation.mark.complete', session('tenant')['permissions']))
                                        <a href="{{route('tenant.user.reservation.mark.complete',$user->id)}}" class="badge text-white bg-success">Booking Requested</a>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
                <div id="noResult" class="alert alert-danger" role="alert" style="display:none;">
                    User not found!
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fname" class="form-label">First name</label>
                        <input type="text" class="form-control" id="fname" name="fname" value="Mark">
                    </div>
                    <div class="mb-3">
                        <label for="lname" class="form-label">Last name</label>
                        <input type="text" class="form-control" id="lname" name="lname" value="Otto">
                    </div>
                    <div class="mb-3">
                        <label for="user_email" class="form-label ">Email</label>
                        <input type="email" class="form-control" id="user_email" name="user_email"
                            value="example@gmail.com">
                    </div>
                    <div class="mb-3">
                        <label for="user_phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="user_phone" name="user_phone" value="+13312323443">
                    </div>
                    <div class="mb-3">
                        <label for="user_password" class="form-label">Address</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="2">Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe, voluptate?</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById("userSearch");
        const rows = document.querySelectorAll("#userTable tbody tr");
        const noResult = document.getElementById("noResult");

        searchInput.addEventListener("keyup", function() {
            let value = this.value.toLowerCase().trim();
            let match = 0;

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                if (text.includes(value) || value === "") {
                    row.style.display = "";
                    if (text.includes(value)) match++;
                } else {
                    row.style.display = "none";
                }
            });

            // ✅ Show/Hide message
            if (value !== "" && match === 0) {
                noResult.style.display = "block";
            } else {
                noResult.style.display = "none";
            }
        });
    </script>
@endsection
