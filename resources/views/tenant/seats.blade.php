{{-- @extends('tenant.layouts.master')

@section('main-content')
<div class="container mt-4">
    <button id="addSeatBtn" class="btn btn-primary mb-3">➕ Add Seat</button>
    <div id="canvas"></div>

    <!-- Hidden color picker -->
    <input type="color" id="colorPicker" class="color-picker">
</div>

<style>
    #canvas {
        width: 100%;
        height: 500px;
        border: 2px dashed #ccc;
        position: relative;
        overflow: hidden;
        background: #f9f9f9;
    }

    .seat {
        width: 40px;
        height: 40px;
        background: #3498db;
        position: absolute;
        top: 10px;
        left: 10px;
        border-radius: 5px;
        cursor: move;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 12px;
        font-weight: bold;
    }

    .color-picker {
        display: none;
        position: absolute;
        background: #fff;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-shadow: 0px 2px 6px rgba(0,0,0,0.2);
    }
</style>

<!-- Interact.js -->
<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
<script>
    let seatCounter = 1;
    const canvas = document.getElementById('canvas');
    const colorPicker = document.getElementById('colorPicker');
    let activeSeat = null;

    // Add new seat
    document.getElementById('addSeatBtn').addEventListener('click', () => {
        const seat = document.createElement('div');
        seat.classList.add('seat');
        seat.textContent = seatCounter++;
        seat.style.top = "20px";
        seat.style.left = "20px";

        // Seat click for color
        seat.addEventListener('click', (e) => {
            e.stopPropagation();
            activeSeat = seat;
            colorPicker.style.display = "block";
            colorPicker.style.top = e.clientY + "px";
            colorPicker.style.left = e.clientX + "px";
            colorPicker.value = rgbToHex(seat.style.backgroundColor);
            colorPicker.click();
        });

        canvas.appendChild(seat);

        makeDraggable(seat);
    });

    // Color change
    colorPicker.addEventListener('input', (e) => {
        if (activeSeat) {
            activeSeat.style.backgroundColor = e.target.value;
        }
    });

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

    // Helper: Convert rgb() to hex
    function rgbToHex(rgb) {
        if (!rgb) return "#3498db";
        const result = rgb.match(/\d+/g);
        return "#" + result.slice(0, 3).map(x =>
            ("0" + parseInt(x).toString(16)).slice(-2)
        ).join('');
    }
</script>
@endsection --}}


@extends('tenant.layouts.master')

@section('main-content')
<div class="container mt-4">
    <button id="addSeatBtn" class="btn btn-primary mb-3">➕ Add Seat</button>
    <div id="canvas"></div>

    <!-- Hidden input for color selection -->
    <input type="hidden" id="selectedColor" name="seat_color">
</div>

<style>
    #canvas {
        width: 100%;
        height: 600px;
        border: 2px dashed #ccc;
        position: relative;
        overflow: hidden;
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
            <li data-color="yellow">Yellow</li>
            <li data-color="blue">Blue</li>
        </ul>
    `;
    document.body.appendChild(contextMenu);

    let activeSeat = null;

    const seatSize = 60;  // bigger box
    const seatGap = 10;   // space between seats
    let nextX = 20;       // track X for next seat
    let nextY = 20;       // track Y for next seat

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

        // Move nextX for the next seat
        nextX += seatSize + seatGap;
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
@endsection
