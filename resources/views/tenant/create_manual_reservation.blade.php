@extends('tenant.layouts.master')

@section('main-content')
    <div class="container py-4">
        <h2 class="mb-4 fw-bold">Book Your Beach Setup!</h2>

        <form id="beach-booking-form">
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
                            <label for="how_many_set" class="form-label">How many sets do you want to reserve?</label>
                            <input type="number" class="form-control" id="how_many_set" name="no_of_sets"
                                placeholder="No. of sets" min="1" required>
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
                            <input type="number" class="form-control" id="addon_seats" name="addon_seats" placeholder="0"
                                min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="addon_umbrella" class="form-label">Number of Extra Umbrellas</label>
                            <input type="number" class="form-control" id="addon_umbrella" name="addon_umbrella"
                                placeholder="0" min="0">
                        </div>
                    </div>

                    <div class="mt-3 border-top pt-3">
                        <p class="mb-1"><strong>Base Total:</strong> <span class="review-base">$0</span></p>
                        <p class="mb-1"><strong>Add-ons Total:</strong> <span class="review-addons">$0</span></p>
                        <p class="fw-bold fs-5">Grand Total: <span class="review-total">$0</span></p>
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
                            <input type="date" class="form-control" id="end_booking_date" name="end_booking_date"
                                required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Notes For Beach Attendants</label>
                            <textarea class="form-control" name="address" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hidden Fields --}}
            <input type="hidden" name="pricing_id" class="pricingId">
            <input type="hidden" name="total_price" class="totalPrice">

            {{-- Submit --}}
            <div class="text-end">
                <button type="button" class="btn btn-primary px-4" onclick="reviewAndSubmit()">Confirm & Submit</button>
            </div>
        </form>
    </div>

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Restrict past dates
            const today = new Date().toISOString().split('T')[0];
            const startDate = document.getElementById('booking_date');
            const endDate = document.getElementById('end_booking_date');
            startDate.min = today;
            endDate.min = today;

            startDate.addEventListener('change', function() {
                endDate.min = this.value;
            });

            // Toggle add-ons
            const addonsToggle = document.getElementById('addonsToggle');
            const addonsContainer = document.querySelector('.addons-container');
            addonsToggle.addEventListener('change', function() {
                addonsContainer.style.display = this.checked ? 'flex' : 'none';
                calculateTotal();
            });

            // Fetch active pricing
            window.pricing = {
                base_set: 65,
                seat: 10,
                umbrella: 5
            };
            fetch('/get-active-pricing')
                .then(res => res.json())
                .then(data => {
                    window.pricing = data;
                })
                .catch(err => console.error(err));

            // Recalculate total whenever numbers change
            $('#how_many_set, #addon_seats, #addon_umbrella').on('input', calculateTotal);
        });

        function calculateTotal() {
            const sets = parseInt($('#how_many_set').val()) || 0;
            const addonSeats = parseInt($('#addon_seats').val()) || 0;
            const addonUmbrella = parseInt($('#addon_umbrella').val()) || 0;

            const basePrice = sets * pricing.base_set;
            const addonPrice = addonSeats * pricing.seat + addonUmbrella * pricing.umbrella;
            const subtotal = basePrice + addonPrice;

            $('.pricingId').val(pricing.priceId ?? '');
            $('.totalPrice').val(subtotal);

            $('.review-base').text(`$${basePrice}`);
            $('.review-addons').text(`$${addonPrice}`);
            $('.review-total').text(`$${subtotal}`);
        }

        function reviewAndSubmit() {
            calculateTotal();

            const sets = $('#how_many_set').val() || 0;
            const addonSeats = $('#addon_seats').val() || 0;
            const addonUmbrella = $('#addon_umbrella').val() || 0;
            const total = $('.totalPrice').val();
            const start = $('#booking_date').val();
            const end = $('#end_booking_date').val();
            const name = `${$('input[name="first_name"]').val()} ${$('input[name="last_name"]').val()}`;
            const tower = $('select[name="tower"]').val();

            // Review modal
            Swal.fire({
                title: 'Review Your Reservation',
                html: `
            <div class="text-start">
                <p><strong>Name:</strong> ${name}</p>
                <p><strong>Tower:</strong> ${tower}</p>
                <p><strong>Booking Dates:</strong> ${start} → ${end}</p>
                <hr>
                <p><strong>Sets:</strong> ${sets}</p>
                <p><strong>Extra Seats:</strong> ${addonSeats}</p>
                <p><strong>Extra Umbrellas:</strong> ${addonUmbrella}</p>
                <hr>
                <p><strong>Total Amount:</strong> $${total}</p>
            </div>
        `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Confirm & Submit',
                cancelButtonText: 'Cancel',
                preConfirm: () => saveReservation()
            });
        }

        function saveReservation() {
    const form = $('#beach-booking-form')[0];
    const formData = new FormData(form);

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    Swal.fire({
        title: 'Processing Reservation...',
        html: 'Please wait while we confirm your booking.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    $.ajax({
        url: '{{ route('tenant.save.manual.reservation') }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            Swal.close();

            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Reservation Successful!',
                    text: response.message ?? 'Your booking has been confirmed.',
                    showConfirmButton: false,
                    timer: 1800
                }).then(() => window.location.reload());
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Reservation Failed',
                    text: response.message ?? 'Unable to process your booking. Please try again.'
                });
            }
        },
        error: function (xhr) {
            Swal.close();

            // Remove old inline validation errors
            $('#beach-booking-form .text-danger').remove();

            // Laravel validation error (422)
            if (xhr.status === 422 && xhr.responseJSON?.errors) {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    const input = $(`[name="${field}"]`);
                    if (input.length > 0) {
                        input.after(`<small class="text-danger d-block mt-1">${messages[0]}</small>`);
                    }
                });

                // Scroll to first invalid field
                const firstErrorField = Object.keys(errors)[0];
                const firstInput = $(`[name="${firstErrorField}"]`);
                if (firstInput.length > 0) {
                    $('html, body').animate({ scrollTop: firstInput.offset().top - 100 }, 600);
                    firstInput.focus();
                }
                return; // stop here, don't show Swal for 422
            }

            // 401: Invalid credentials
            if (xhr.status === 401) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Credentials',
                    text: 'The email or password you entered is incorrect.'
                });
                return;
            }

            // 403: Not enough inventory or permission issue
            if (xhr.status === 403) {
                const seats = xhr.responseJSON?.available_seats ?? 0;
                const umbrellas = xhr.responseJSON?.available_umbrellas ?? 0;
                const msg = xhr.responseJSON?.errors ??
                    'No tenant has sufficient inventory matching your selection.';

                Swal.fire({
                    icon: 'warning',
                    title: 'Reservation Failed',
                    html: `
                        <p>${msg}</p>
                        <hr>
                        <p><strong>Available Seats:</strong> ${seats}</p>
                        <p><strong>Available Umbrellas:</strong> ${umbrellas}</p>
                    `
                });
                return;
            }

            // Custom backend message
            if (xhr.responseJSON?.message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON.message
                });
                return;
            }

            // Default fallback
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: 'Something went wrong. Please try again later.'
            });
        }
    });
}

    </script>
@endsection
