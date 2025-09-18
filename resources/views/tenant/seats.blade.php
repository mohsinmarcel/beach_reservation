{{-- @extends('tenant.layouts.master')

@section('main-content')
<div class="container mt-4">
    <button id="addSeatBtn" class="btn btn-primary mb-3">➕ Add Seat</button>
    <div id="canvas"></div>

    <!-- Hidden input for color selection -->
    <form id="seatForm">
        @csrf
        <input type="hidden" id="selectedColor" name="seat_color">
        <input type="button" onclick="saveSeatings()" value="Save Seatings" class="btn btn-primary mt-3">
    </form>
</div>

<style>
    #canvas {
        width: 103%;             /* full width */
        height: 700px;           /* fixed height */
        border: 2px dashed #ccc;
        position: relative;
        overflow: auto;          /* allow scroll if overflow */
        background: #f9f9f9;
        padding: 20px;
    }

    .seat {
        width: 60px;   /* bigger seat */
        height: 60px;
        background: #3498db;
        position: absolute;
        border-radius: 6px;
        cursor: move;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 14px;
        font-weight: bold;
    }

    /* Context menu */
    #contextMenu {
        display: none;
        position: absolute;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-shadow: 0px 2px 6px rgba(0,0,0,0.2);
        z-index: 1000;
        min-width: 120px;
    }

    #contextMenu ul {
        list-style: none;
        margin: 0;
        padding: 5px 0;
    }

    #contextMenu ul li {
        padding: 8px 12px;
        cursor: pointer;
    }

    #contextMenu ul li:hover {
        background: #f0f0f0;
    }
</style>

<!-- Interact.js -->
<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
<script>
    let seatCounter = 1;
    const canvas = document.getElementById('canvas');
    const contextMenu = document.createElement('div');
    contextMenu.id = 'contextMenu';
    contextMenu.innerHTML = `
        <ul>
            <li data-color="red">Red</li>
            <li data-color="orange">Orange</li>
            <li data-color="blue">Blue</li>
        </ul>
    `;
    document.body.appendChild(contextMenu);

    let activeSeat = null;

    const seatSize = 60;  // seat size
    const seatGap = 10;   // small gap
    let nextX = 20;       // X for next seat
    let nextY = 20;       // Y for next seat
    const maxSeatsPerRow = 20; // limit per row
    let seatInRow = 0;

    // Add new seat
    document.getElementById('addSeatBtn').addEventListener('click', () => {
        const seat = document.createElement('div');
        seat.classList.add('seat');
        seat.textContent = seatCounter++;
        seat.style.top = nextY + "px";
        seat.style.left = nextX + "px";

        // Right click seat → open menu
        seat.addEventListener('contextmenu', (e) => {
            e.preventDefault();
            activeSeat = seat;
            showContextMenu(e.clientX, e.clientY);
        });

        canvas.appendChild(seat);
        makeDraggable(seat);

        // Move X for next seat
        nextX += seatSize + seatGap;
        seatInRow++;

        // Wrap to next row if reached max
        if (seatInRow >= maxSeatsPerRow) {
            nextX = 20;
            nextY += seatSize + seatGap;
            seatInRow = 0;
        }
    });

    // Context menu actions
    contextMenu.addEventListener('click', (e) => {
        if (e.target.dataset.color && activeSeat) {
            const color = e.target.dataset.color;
            activeSeat.style.backgroundColor = color;
            document.getElementById('selectedColor').value = color;
            hideContextMenu();
        }
    });

    // Hide context menu on click elsewhere
    document.addEventListener('click', () => hideContextMenu());

    function showContextMenu(x, y) {
        contextMenu.style.display = "block";
        contextMenu.style.left = x + "px";
        contextMenu.style.top = y + "px";
    }

    function hideContextMenu() {
        contextMenu.style.display = "none";
    }

    // Make draggable with interact.js
    function makeDraggable(el) {
        interact(el).draggable({
            listeners: {
                move(event) {
                    const target = event.target;
                    const x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
                    const y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

                    target.style.transform = `translate(${x}px, ${y}px)`;
                    target.setAttribute('data-x', x);
                    target.setAttribute('data-y', y);
                }
            }
        });
    }
</script>
@endsection --}}

@extends('tenant.layouts.master')

@section('main-content')
<div class="container mt-4">
    <button id="addSeatBtn" class="btn btn-primary mb-3">➕ Add Seat</button>
    <div id="canvas"></div>

    <!-- Hidden form for submission -->
    <form id="seatForm">
        @csrf
        <input type="hidden" id="seatsData" name="seats">  {{-- all seats array will go here --}}
        <input type="button" onclick="saveSeatings()" value="Save Seatings" class="btn btn-primary mt-3">
    </form>
</div>

<style>
    #canvas {
        width: 103%;
        height: 700px;
        border: 2px dashed #ccc;
        position: relative;
        overflow: auto;
        background: #f9f9f9;
        padding: 20px;
    }

    .seat {
        width: 60px;
        height: 60px;
        background: #3498db;
        position: absolute;
        border-radius: 6px;
        cursor: move;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 14px;
        font-weight: bold;
    }

    #contextMenu {
        display: none;
        position: absolute;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-shadow: 0px 2px 6px rgba(0,0,0,0.2);
        z-index: 1000;
        min-width: 120px;
    }

    #contextMenu ul {
        list-style: none;
        margin: 0;
        padding: 5px 0;
    }

    #contextMenu ul li {
        padding: 8px 12px;
        cursor: pointer;
    }

    #contextMenu ul li:hover {
        background: #f0f0f0;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
<script>
    let seatCounter = 1;
    const canvas = document.getElementById('canvas');
    const contextMenu = document.createElement('div');
    contextMenu.id = 'contextMenu';
    contextMenu.innerHTML = `
        <ul>
            <li data-color="red">Red</li>
            <li data-color="orange">Orange</li>
            <li data-color="blue">Blue</li>
        </ul>
    `;
    document.body.appendChild(contextMenu);

    let activeSeat = null;
    const seatSize = 60;
    const seatGap = 10;
    let nextX = 20;
    let nextY = 20;
    const maxSeatsPerRow = 20;
    let seatInRow = 0;

    // Add new seat
    document.getElementById('addSeatBtn').addEventListener('click', () => {
        const seat = document.createElement('div');
        seat.classList.add('seat');
        seat.textContent = seatCounter;
        seat.dataset.seatNumber = seatCounter;   // store seat number
        seat.dataset.color = "blue";             // default color
        seat.style.top = nextY + "px";
        seat.style.left = nextX + "px";

        // Right-click to change color
        seat.addEventListener('contextmenu', (e) => {
            e.preventDefault();
            activeSeat = seat;
            showContextMenu(e.clientX, e.clientY);
        });

        canvas.appendChild(seat);
        makeDraggable(seat);

        seatCounter++;
        nextX += seatSize + seatGap;
        seatInRow++;

        if (seatInRow >= maxSeatsPerRow) {
            nextX = 20;
            nextY += seatSize + seatGap;
            seatInRow = 0;
        }
    });

    // Context menu actions
    contextMenu.addEventListener('click', (e) => {
        if (e.target.dataset.color && activeSeat) {
            const color = e.target.dataset.color;
            activeSeat.style.backgroundColor = color;
            activeSeat.dataset.color = color; // save selected color
            hideContextMenu();
        }
    });

    document.addEventListener('click', () => hideContextMenu());

    function showContextMenu(x, y) {
        contextMenu.style.display = "block";
        contextMenu.style.left = x + "px";
        contextMenu.style.top = y + "px";
    }

    function hideContextMenu() {
        contextMenu.style.display = "none";
    }

    // Draggable seats
    function makeDraggable(el) {
        interact(el).draggable({
            listeners: {
                move(event) {
                    const target = event.target;
                    const x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
                    const y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

                    target.style.transform = `translate(${x}px, ${y}px)`;
                    target.setAttribute('data-x', x);
                    target.setAttribute('data-y', y);
                }
            }
        });
    }

    // Collect all seats and submit
    function saveSeatings() {
        const seats = [];
        document.querySelectorAll('.seat').forEach(seat => {
            seats.push({
                seat_number: seat.dataset.seatNumber,
                seat_color: seat.dataset.color
            });
        });

        document.getElementById('seatsData').value = JSON.stringify(seats);

        // Example: submit via AJAX
        fetch("{{ route('tenant.seats.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ seats })
        })
        .then(res => res.json())
        .then(data => {
            alert("Seats saved successfully!");
        })
        .catch(err => console.error(err));
    }
</script>
@endsection
