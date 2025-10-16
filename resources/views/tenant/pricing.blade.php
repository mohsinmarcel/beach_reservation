@extends('tenant.layouts.master')

@section('main-content')
<div class="main w-100 pt-4 pe-4">
        <div class="container">
            <a href="javascript:void(0)" class="btn btn-primary" id="addSeasonBtn">Add Season</a>
            <div class="d-flex justify-content-between my-4">
                <h1 class="mb-0">All Pricings </h1>
                <input type="search" class="form-control w-25" id="userSearch" placeholder="Search user...">
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered w-100" id="userTable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Season Name</th>
                            <th scope="col">Price Per Seat</th>
                            <th scope="col">Price Per Umbrella</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @if (!empty($pricing) && $pricing->count() > 0)
                            @foreach ($pricing as $index => $user)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $user->name }}</td>
                                   <td>${{ number_format($user->price_per_seat, 2) }}</td>
                                      <td>${{ number_format($user->price_per_umbrella, 2) }}</td>
                                      @if ($user->is_active == 1)
                                      <td class="text-center">
                                        <button type="button" class="btn btn-success" >Applied</button>
                                        </td>
                                        @else
                                        <td class="text-center">
                                        <button type="button" class="btn btn-primary" onclick="window.location.href='{{route('tenant.pricing.update',$user->id)}}'">Apply New Rate To Inventory</button>
                                        </td>
                                      @endif

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$('#addSeasonBtn').on('click', function() {
    Swal.fire({
        title: 'Add New Season',
        html:
            '<input id="seasonName" class="swal2-input" placeholder="Season Name">' +
            '<input id="pricePerSeat" type="number" class="swal2-input" placeholder="Price Per Seat">' +
            '<input id="pricePerUmbrella" type="number" class="swal2-input" placeholder="Price Per Umbrella">',
        confirmButtonText: 'Save',
        showCancelButton: true,
        cancelButtonColor: '#d33',
        preConfirm: () => {
            const seasonName = $('#seasonName').val().trim();
            const pricePerSeat = $('#pricePerSeat').val().trim();
            const pricePerUmbrella = $('#pricePerUmbrella').val().trim();

            if (!seasonName || !pricePerSeat || !pricePerUmbrella) {
                Swal.showValidationMessage('All fields are required');
                return false;
            }

            return { seasonName, pricePerSeat, pricePerUmbrella };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('tenant.pricing.store') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    season_name: result.value.seasonName,
                    price_per_seat: result.value.pricePerSeat,
                    price_per_umbrella: result.value.pricePerUmbrella
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Season added successfully.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    setTimeout(() => location.reload(), 1500);
                },
                error: function(xhr) {
                    let msg = 'Something went wrong!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: msg
                    });
                }
            });
        }
    });
});
</script>


@endsection
