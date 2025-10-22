@extends('tenant.layouts.master')

@section('main-content')
<div class="container py-4">
    <h2 class="mb-4 fw-bold">Book Your Beach Setup!</h2>
    <form id="beach-booking-form" method="POST" action="{{ route('user.reserve.booking.login') }}">
        @csrf

        {{-- Beach Setup Details --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Beach Set – Perfect for Two</h5>
                <p class="text-muted mb-3" style="font-size: 14px;">
                    Enjoy a relaxing day by the sea with 2 comfortable sunbeds and 1 umbrella.
                    Add extra seats or umbrellas if needed.
                </p>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="no_of_sets" class="form-label">How many sets do you want to reserve?</label>
                        <input type="number" class="form-control" id="no_of_sets" name="no_of_sets" placeholder="No. of sets" min="1" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label d-block">Add-ons</label>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="addonsToggle">
                            <label class="form-check-label" for="addonsToggle">Need extra seat or umbrella?</label>
                        </div>
                    </div>
                </div>

                <div class="row addons-container" style="display:none;">
                    <div class="col-md-6 mb-3">
                        <label for="addon_seats" class="form-label">Number of Extra Seats</label>
                        <input type="number" class="form-control" id="addon_seats" name="addon_seats" placeholder="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="addon_umbrella" class="form-label">Number of Extra Umbrellas</label>
                        <input type="number" class="form-control" id="addon_umbrella" name="addon_umbrella" placeholder="0">
                    </div>
                </div>
            </div>
        </div>

        {{-- Personal Details --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Your Details</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" name="phone_number" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control" name="city" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">State</label>
                        <input type="text" class="form-control" name="state" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unit Number</label>
                        <input type="text" class="form-control" name="room_number" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tower</label>
                        <select class="form-select" name="tower" required>
                            <option value="east_tower">East Tower</option>
                            <option value="west_tower">West Tower</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Booking Date</label>
                        <input type="date" class="form-control" id="booking_date" name="booking_date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Booking Date</label>
                        <input type="date" class="form-control" id="end_booking_date" name="end_booking_date" required>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Notes For Beach Attendants</label>
                        <textarea class="form-control" name="address" rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Details --}}
        {{-- <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Payment Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name On Card</label>
                        <input type="text" class="form-control" name="name_on_card" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Card Number</label>
                        <input type="text" class="form-control" name="card_number" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Security Code (CVC)</label>
                        <input type="text" class="form-control" name="cvc" maxlength="4" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Expiry Month</label>
                        <input type="text" class="form-control" name="expiry_month" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Expiry Year</label>
                        <input type="text" class="form-control" name="expiry_year" required>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- Hidden Fields --}}
        <input type="hidden" name="pricing_id" class="pricingId" value>
        <input type="hidden" name="total_price" class="totalPrice">

        {{-- Submit --}}
        <div class="text-end">
            <button type="button" class="btn btn-primary px-4" onclick="saveReservation(this)">Confirm & Submit</button>
        </div>
    </form>
</div>

{{-- JS for date restriction & addons toggle --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prevent past dates
        const today = new Date().toISOString().split('T')[0];
        const startDate = document.getElementById('booking_date');
        const endDate = document.getElementById('end_booking_date');
        startDate.min = today;
        endDate.min = today;

        startDate.addEventListener('change', function() {
            endDate.min = this.value;
        });

        // Toggle add-ons section
        const addonsToggle = document.getElementById('addonsToggle');
        const addonsContainer = document.querySelector('.addons-container');
        addonsToggle.addEventListener('change', function() {
            addonsContainer.style.display = this.checked ? 'flex' : 'none';
        });
    });

    function saveReservation()
    {
        console.log('Saving reservation...');
    }

</script>
@endsection
