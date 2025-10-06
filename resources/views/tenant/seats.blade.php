@extends('tenant.layouts.master')

@section('main-content')
<div class="container mt-4">

    <!-- ========== Seat Form ========== -->
    <h4>🎟 Manage Seats</h4>
    <!-- Success/Error Message Display (Add this for feedback) -->
    <div id="seatFormMessages" class="alert d-none"></div>

    <button type="button" class="btn btn-success mt-3 mb-3 addSeatRow">➕ Add Seat</button>
    <form id="seatForm" method="POST" action="{{route('tenant.seats.store')}}">
        @csrf
        <div id="seatRows">
            <!-- Initial Seat Row (Used for cloning) -->
            <div class="row g-2 seatRow mt-2">
                <div class="col-md-2">
                    <!-- The value will be set by JS on page load based on existing records -->
                    <input type="text" name="seats[0][code]" class="form-control seatCode"
                           value="" readonly placeholder="S-{{session('tenant')['tenant']['id']}}-001">
                </div>

                <div class="col-md-2">
                    <select name="seats[0][row]" class="form-control">
                        <option value="">-- Select Row --</option>
                        <option value="first">First Row</option>
                        <option value="second">Second Row</option>
                        <option value="third">Third Row</option>
                        <option value="fourth">Fourth Row</option>
                        <option value="fifth">Fifth Row</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="seats[0][category]" class="form-control">
                        <option value="">-- Select Category --</option>
                        <option value="normal">Normal</option>
                        <option value="executive">Executive</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="number" step="0.01" name="seats[0][price]" class="form-control" placeholder="Price">
                </div>

                <div class="col-md-2 d-flex align-items-center">
                    <button type="button" class="btn btn-danger ms-1 removeSeatRow d-none">❌</button>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Save Seats</button>
    </form>

    <hr class="my-5">

    <!-- ========== Existing Seats List ========== -->
    <h4>📊 Existing Seats ({{ count($tenantInventorySeats ?? []) }})</h4>
    @if (!empty($tenantInventorySeats))
    <div class="table-responsive">
        <table class="table table-striped table-hover mt-3">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Row</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tenantInventorySeats as $seat)
                <tr>
                    <td>{{ $seat->serial_no }}</td>
                    <td>{{ ucfirst($seat->row) }}</td>
                    <td>{{ ucfirst($seat->category) }}</td>
                    <td>${{ number_format($seat->price, 2) }}</td>
                    <td><span class="badge bg-{{ $seat->status == 'available' ? 'success' : 'danger' }}">{{ ucfirst($seat->status) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="mt-3">No seats have been added yet.</p>
    @endif

    <hr class="my-5">

    <!-- ========== Umbrella Form ========== -->
    <h4>🌂 Manage Umbrellas</h4>
    <!-- Success/Error Message Display (Add this for feedback) -->
    <div id="umbrellaFormMessages" class="alert d-none"></div>

    <button type="button" class="btn btn-success mt-2 mb-3 addUmbrellaRow">➕ Add Umbrella</button>
    <form id="umbrellaForm" method="POST" action="{{route('tenant.umbrellas.store')}}">
        @csrf
        <div id="umbrellaRows">
            <!-- Initial Umbrella Row (Used for cloning) -->
            <div class="row g-2 umbrellaRow mt-2">
                <div class="col-md-2">
                    <!-- The value will be set by JS on page load based on existing records -->
                    <input type="text" name="umbrellas[0][umbrella_number]" class="form-control umbrellaNumber"
                           value="" readonly placeholder="UM-{{session('tenant')['tenant']['id']}}-001">
                </div>
                <div class="col-md-2">
                    <select name="umbrellas[0][category]" class="form-control">
                        <option value="">-- Select Category --</option>
                        <option value="normal">Normal</option>
                        <option value="executive">Executive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="umbrellas[0][price]" class="form-control" placeholder="Price">
                </div>
                <div class="col-md-2 d-flex align-items-center">
                    <button type="button" class="btn btn-danger ms-1 removeUmbrellaRow d-none">❌</button>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Save Umbrellas</button>
    </form>

    <hr class="my-5">

    <!-- ========== Existing Umbrellas List ========== -->
    <h4>📊 Existing Umbrellas ({{ count($tenantInventoryUmbrellas ?? []) }})</h4>
    @if (!empty($tenantInventoryUmbrellas))
    <div class="table-responsive">
        <table class="table table-striped table-hover mt-3">
            <thead>
                <tr>
                    <th>Number</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tenantInventoryUmbrellas as $umbrella)
                <tr>
                    <td>{{ $umbrella->serial_no }}</td>
                    <td>{{ ucfirst($umbrella->category) }}</td>
                    <td>${{ number_format($umbrella->price, 2) }}</td>
                    <td><span class="badge bg-{{ $umbrella->status == 'available' ? 'success' : 'danger' }}">{{ ucfirst($umbrella->status) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="mt-3">No umbrellas have been added yet.</p>
    @endif
</div>

<script>
    // --- Data Initialization from PHP ---
    const tenantId = "{{ session('tenant')['tenant']['id'] }}";
    const existingSeats = @json($tenantInventorySeats ?? []);
    const existingUmbrellas = @json($tenantInventoryUmbrellas ?? []);

    function calculateStartingIndex(existingItems, prefix) {
        let maxSerial = 0;
        const regex = new RegExp(`^${prefix}-\\d+-(\\d{3})$`);
        existingItems.forEach(item => {
            const code = item.serial_no; // <-- use serial_no from DB
            const match = code.match(regex);
            if (match && match[1]) {
                const serial = parseInt(match[1], 10);
                if (serial > maxSerial) {
                    maxSerial = serial;
                }
            }
        });
        return maxSerial;
    }

    function generateNewCode(prefix, index) {
        return prefix + "-" + tenantId + "-" + String(index).padStart(3, '0');
    }

    // --- Initialize next available indexes ---
    let seatIndex = calculateStartingIndex(existingSeats, 'S') + 1;
    let umbrellaIndex = calculateStartingIndex(existingUmbrellas, 'UM') + 1;

    // --- Set initial codes on page load ---
    document.addEventListener('DOMContentLoaded', () => {
        const initialSeatCodeInput = document.querySelector('.seatRow .seatCode');
        if (initialSeatCodeInput) {
            initialSeatCodeInput.value = generateNewCode('S', seatIndex++);
        }
        const initialUmbrellaCodeInput = document.querySelector('.umbrellaRow .umbrellaNumber');
        if (initialUmbrellaCodeInput) {
            initialUmbrellaCodeInput.value = generateNewCode('UM', umbrellaIndex++);
        }
    });

    // --- Seats Event Listeners ---
    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("addSeatRow")) {
            const firstRow = document.querySelector(".seatRow");
            if (!firstRow) return;

            const row = firstRow.cloneNode(true);

            row.querySelectorAll("input, select").forEach(el => {
                let name = el.getAttribute("name");
                if (name) {
                    name = name.replace(/\[\d+\]/, "[" + seatIndex + "]");
                    el.setAttribute("name", name);
                }

                if (el.classList.contains("seatCode")) {
                    el.value = generateNewCode('S', seatIndex++);
                } else if (el.tagName === "INPUT") {
                    el.value = "";
                } else if (el.tagName === "SELECT") {
                    el.selectedIndex = 0;
                }
            });

            row.querySelector(".removeSeatRow").classList.remove("d-none");
            document.getElementById("seatRows").appendChild(row);
        }

        if (e.target.classList.contains("removeSeatRow")) {
            const rows = document.querySelectorAll(".seatRow");
            if (rows.length > 1) {
                e.target.closest(".seatRow").remove();
            }
        }
    });

    // --- Umbrellas Event Listeners ---
    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("addUmbrellaRow")) {
            const firstRow = document.querySelector(".umbrellaRow");
            if (!firstRow) return;

            const row = firstRow.cloneNode(true);

            row.querySelectorAll("input, select").forEach(el => {
                let name = el.getAttribute("name");
                if (name) {
                    name = name.replace(/\[\d+\]/, "[" + umbrellaIndex + "]");
                    el.setAttribute("name", name);
                }
                if (el.classList.contains("umbrellaNumber")) {
                    el.value = generateNewCode('UM', umbrellaIndex++);
                } else if (el.tagName === "INPUT") {
                    el.value = "";
                } else if (el.tagName === "SELECT") {
                    el.selectedIndex = 0;
                }
            });

            row.querySelector(".removeUmbrellaRow").classList.remove("d-none");
            document.getElementById("umbrellaRows").appendChild(row);
        }

        if (e.target.classList.contains("removeUmbrellaRow")) {
            const rows = document.querySelectorAll(".umbrellaRow");
            if (rows.length > 1) {
                e.target.closest(".umbrellaRow").remove();
            }
        }
    });
</script>

@endsection
