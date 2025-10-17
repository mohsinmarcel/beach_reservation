<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('userside_assets/assets/css/custom.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Beach Chair Reservation</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg shadow position-fixed w-100">
        <div class="container">
            <a class="navbar-brand" href="index.html"><img src="{{ asset('userside_assets/assets/images/logo.png') }}"
                    width="200" alt="logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-3">
                    <li class="nav-item">
                        <a class="nav-link text-white active" aria-current="page" href="index.html">Home</a>
                    </li>
                    <li class="nav-item"><a class="nav-link text-white" href="#">About Us</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#">Services</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#">Blogs</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#">Contact Us</a></li>
                </ul>
            </div>
            <!-- <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    John Cena
                </button>
                <ul class="dropdown-menu">
                    <li class="nav-item"><a href="booking.html" class="dropdown-item">Booking</a></li>
                    <li class="nav-item"><a href="cancellation.html" class="dropdown-item">Cancellation</a></li>
                    <li class="nav-item"><a href="#" class="dropdown-item">Reminder</a></li>
                </ul>
            </div> -->
            {{-- @dd(session('user')) --}}
            @if (!empty(session('user')))
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Welcome, {{ ucWords(session('user')->name) }}
                    </button>
                    <ul class="dropdown-menu">
                        <li class="nav-item"><a href="{{ route('user.bookings') }}" class="dropdown-item">Booking</a>
                        </li>
                        <li class="nav-item"><a href="{{ route('user.cancellations') }}"
                                class="dropdown-item">Cancellation</a></li>
                        <li class="nav-item"><a href="{{ route('user.reminders') }}" class="dropdown-item">Reminder</a>
                        </li>
                        <li class="nav-item"><a href="{{ route('user.logout') }}" class="dropdown-item">Logout</a></li>
                    </ul>
                </div>
            @else
                <button type="button" class="btn btn-outline-light rounded-pill" data-bs-toggle="modal"
                    data-bs-target="#userModal">Login</button>
            @endif
            <!-- Modal -->
            <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form id="loginForm">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-4">
                                    <h1 class="modal-title text-black fs-1 fw-bold lh-sm mb-2" id="userModalLabel">Login
                                    </h1>
                                    <p class="text-secondary fs-6 sw-normal lh-sm">Welcome Back! Please login to your
                                        account.</p>
                                </div>
                                <div class="mb-3">
                                    <label for="secret_code" class="form-label fw-semibold lh-sm"
                                        style="font-size: 14px;">Code</label>
                                    <input type="text" class="form-control" id="secret_code" name="unique_code">
                                </div>
                                <div class="mb-3">
                                    <label for="user_password" class="form-label fw-semibold lh-sm"
                                        style="font-size: 14px;">Password</label>
                                    <input type="password" class="form-control" id="user_password"
                                        name="login_password">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="button" class="btn btn-lg btn-primary" value="Login"
                                    onclick="signIn()">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <main>
        <!-- Hero Start -->
        <section class="hero-block overflow-hidden position-relative">
            <div class="container-fluid p-0">
                <div class="hero-video">
                    <video autoplay loop preload="auto" muted tabindex="0" style="object-fit:cover;">
                        <source src="{{ asset('userside_assets/assets/images/hero-vd.mp4') }}" type="video/mp4">
                        <!-- H.264 -->
                        Your browser does not support the video tag.
                    </video>
                    <div class="video-caption text-center position-absolute">
                        <h1 class="text-white text">Silver Beach</h1>
                        <h4 class="text-white text">Towers</h4>
                    </div>
                </div>
            </div>
        </section>
        <!-- Hero End -->
        <!-- About Start -->
        <section class="about-block pt-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5">
                        <div class="about-img">
                            <img src="{{ asset('userside_assets/assets/images/about-img.jpeg') }}"
                                class="img-fluid rounded-5" alt="">
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <strong class="fs-6 fw-bold lh-sm">About Us</strong>
                        <h4 class="fw-bold">Beach Sitting</h4>
                        <p class="fs-6 lh-base">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque
                            feugiat ultrices consequat. Nunc et mauris sit amet enim dapibus dignissim eget eget orci.
                            Nulla tincidunt erat et tempor faucibus. Lorem ipsum dolor sit amet, consectetur adipiscing
                            elit. Nunc imperdiet dui neque, et congue lectus pellentesque in. Maecenas lacinia tortor
                            turpis, vitae pulvinar sapien posuere eu. Cras ligula felis, efficitur vel nunc quis,
                            tincidunt blandit sapien. Class aptent taciti sociosqu ad litora torquent per conubia
                            nostra, per inceptos himenaeos. Donec at magna accumsan, porta mi ac, euismod tellus. Fusce
                            eu tincidunt ante. Fusce ipsum arcu, luctus ac purus nec, tempus posuere felis.</p>
                        <p class="fs-6 lh-base">Nunc mi nibh, ornare eget cursus eu, ornare ut leo. Praesent accumsan,
                            massa cursus facilisis laoreet, odio sapien fermentum turpis, ultrices laoreet mauris elit
                            non nunc. Morbi vel nisi commodo, vulputate neque in, dapibus orci. Ut iaculis enim posuere
                            rutrum tincidunt. Proin in nisi porta, vehicula tortor et, malesuada nunc. Nullam elementum
                            ipsum at sollicitudin fermentum. Praesent vestibulum mattis leo, at laoreet sapien egestas
                            quis. Donec viverra feugiat sapien vitae porttitor.</p>
                        <a href="#" class="btn btn-lg btn-primary">Learn more</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- About End -->
        <!-- Booking Start -->
        <section class="booking-block py-5">
            <h2 class="text-center mb-4">Book Your Slot</h2>
            <div class="container">
                <!-- Filters Start -->
                {{-- <div id="filterContainer" class="row position-relative bg-white shadow p-4 rounded">
                    <div class="col-lg-4">
                        <select id="#filterGuests" class="form-select">
                            <option selected>Guests</option>
                            <option value="2">2</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="8">8</option>
                            <option value="10">10</option>
                            <option value="12">12</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <select id="#filterTime" class="form-select">
                            <option selected>Time</option>
                            <option value="9am-to-10am">9 AM to 10 AM</option>
                            <option value="10am-to-11am">10 AM to 11 AM</option>
                            <option value="11am-to-12pm">11 AM to 12 PM</option>
                            <option value="12pm-to-1pm">12 PM to 1 PM</option>
                            <option value="1pm-to-2pm">1 PM to 2 PM</option>
                            <option value="2pm-to-3pm">2 PM to 3 PM</option>
                            <option value="3pm-to-4pm">3 PM to 4 PM</option>
                            <option value="4pm-to-5pm">4 PM to 5 PM</option>
                            <option value="5pm-to-6pm">5 PM to 6 PM</option>

                        </select>
                    </div>
                    <div class="col-lg-4">
                        <select id="#filterDate" class="form-select">
                            <option selected>Date</option>
                            <option value="20/09/2025">20/09/2025</option>
                            <option value="21/09/2025">21/09/2025</option>
                            <option value="22/09/2025">22/09/2025</option>
                            <option value="23/09/2025">23/09/2025</option>
                            <option value="24/09/2025">24/09/2025</option>
                            <option value="25/09/2025">25/09/2025</option>
                            <option value="26/09/2025">26/09/2025</option>
                            <option value="27/09/2025">27/09/2025</option>
                            <option value="28/09/2025">28/09/2025</option>
                            <option value="29/09/2025">29/09/2025</option>
                            <option value="30/09/2025">30/09/2025</option>
                        </select>
                    </div>
                </div> --}}
                <!-- Filters End -->
                <!-- Card Rows Start -->
                <div class="booking-card-row row mt-5" id="cardRow">
                    <div class="col-12 card-block mb-3" data-guests="12" data-time="9am-to-10am"
                        data-date="26/09/2025">
                        <div class="card border-0 bg-white shadow">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <a href="#" class="img-anchor p-2 text-decoration-none d-inline-block">
                                        <img src="{{ asset('userside_assets/assets/images/img-1.webp') }}"
                                            class="img-fluid rounded" alt="image">
                                    </a>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card-content p-3">
                                        <h3 class="card-title fw-bold lh-sm mb-3">Beach Set – Perfect for Two</h3>
                                        <p class="card-short-berif fw-ligth lh-sm mb-2 text-black">Enjoy your relaxing
                                            day by the sea with our premium beach set — includes 2 comfortable sunbeds
                                            and 1 umbrella for perfect shade.</p>
                                        <p class="card-short-berif fw-ligth lh-sm mb-2 text-black">Spend quality time
                                            with your partner or friend while soaking up the sun and the view.</p>
                                        <p class="card-price fs-6 fw-semibold lh-sm mb-2 text-black">Starting From
                                            <span class="currency-symbol">$</span><span class="currency">65</span> /
                                            Set</p>
                                        <p class="card-short-berif fw-ligth lh-sm my-4 text-black">☀️ Limited slots
                                            available — book early to reserve your spot!</p>
                                        <button type="button" class="btn btn-outline-primary rounded-pill w-50 p-3"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            Book Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Card Rows End -->
            </div>
        </section>
        <!-- Booking End -->
        <!-- Review Start -->
        {{-- <section class="reviews-block position-relative" style="background-image: url({{asset('userside_assets/assets/images/img-1.webp')}});">
            <div class="container">
                <h2 class="fw-semibold text-white text-center">Reviews</h2>
                <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <!-- SLIDE 1 -->
                        <div class="carousel-item active">
                            <div class="row g-3">
                                <div class="col-12 col-md-6 py-4">
                                    <div class="p-4 text-center bg-white border rounded">
                                        <img src="{{asset('userside_assets/assets/images/user-default-img.png')}}" class="rounded-circle mb-3" width="80" height="80" alt="Client">
                                        <p>"Great service, fast delivery!"</p>
                                        <h6>- John Doe</h6>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 py-4">
                                    <div class="p-4 text-center bg-white border rounded">
                                        <img src="{{asset('userside_assets/assets/images/user-default-img.png')}}" class="rounded-circle mb-3" width="80" height="80" alt="Client">
                                        <p>"Amazing experience!"</p>
                                        <h6>- Sarah Smith</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SLIDE 2 -->
                        <div class="carousel-item">
                            <div class="row g-3">
                                <div class="col-12 col-md-6 py-4">
                                <div class="p-4 text-center bg-white border rounded">
                                    <img src="{{asset('userside_assets/assets/images/user-default-img.png')}}" class="rounded-circle mb-3" width="80" height="80" alt="Client">
                                    <p>"Excellent support and service!"</p>
                                    <h6>- Emma Watson</h6>
                                </div>
                                </div>
                                <div class="col-12 col-md-6 py-4">
                                <div class="p-4 text-center bg-white border rounded">
                                    <img src="{{asset('userside_assets/assets/images/user-default-img.png')}}" class="rounded-circle mb-3" width="80" height="80" alt="Client">
                                    <p>"Highly recommend to everyone."</p>
                                    <h6>- Chris Brown</h6>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
            </div>
        </section> --}}
        <!-- Review End -->
    </main>
    <!-- Modal Start -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 pt-4">
                    <div class="progress px-1" style="height: 3px;">
                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="step-container d-flex justify-content-between">
                        <div class="step-circle" onclick="displayStep(1)">1</div>
                        <div class="step-circle" onclick="displayStep(2)">2</div>
                        <div class="step-circle" onclick="displayStep(3)">3</div>
                        <div class="step-circle" onclick="displayStep(4)">4</div>
                    </div>
                    <form id="multi-step-form">
                        @csrf
                        <div class="step step-1">
                            <!-- Step 1 form fields here -->
                            <div class="mb-3">
                                <div class="berif-content">
                                    <h3 class="fs-3 fw-bold">Beach Set – Perfect for Two</h3>
                                    <p style="font-size: 14px;">Enjoy your relaxing day by the sea with our premium
                                        beach set — includes 2 comfortable sunbeds and 1 umbrella for perfect shade.</p>
                                    <p style="font-size: 14px;">Spend quality time with your partner or friend while
                                        soaking up the sun and the view.</p>
                                    <p style="font-size: 14px;">☀️ Limited slots available — book early to reserve your
                                        spot!</p>
                                    <img src="{{ asset('userside_assets/assets/images/img-1.webp') }}"
                                        class="img-fluid rounded mb-3" alt="img">
                                    <div class="fw-normal mb-3 text-black" style="font-size: 14px;">
                                        Enjoy a Complete Beach Set (2 Seats and 1 Umbrella) for Perfect Shade.
                                    </div>
                                    <div class="">
                                        <div class="mb-3 d-flex align-items-center gap-2">
                                            <label for="how_many_set" class="form-label fw-medium"
                                                style="font-size: 14px;">How many sets do you want to reserve?</label>
                                            <input type="number" class="form-control w-25" id="how_many_set"
                                                placeholder="No of sets" style="font-size: 14px;" name="no_of_sets">
                                        </div>
                                        <div class="more-seats">
                                            <input type="checkbox" class="form-check-input me-1" value=""
                                                id="more_addons">
                                            <label for="more_addons" class="form-label fw-normal"
                                                style="font-size: 14px;">More seats and umbrella?</label>
                                        </div>
                                        <div class="addons-container" style="display:none;">
                                            <div class="d-flex align-item-center gap-2">
                                                <div class="addon-seats">
                                                    <label for="addon_seats" class="form-label"
                                                        style="font-size: 14px;">Number of Seats</label>
                                                    <input type="number" class="form-control w-50" id="addon_seats"
                                                        name="addon_seats">
                                                </div>
                                                <div class="addon-umberella">
                                                    <label for="addon_umbrella" class="form-label"
                                                        style="font-size: 14px;">Number of Umbrella</label>
                                                    <input type="number" class="form-control w-50"
                                                        id="addon_umbrella" name="addon_umbrella">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-primary next-step">Next</button>
                            </div>
                        </div>
                        <div class="step step-2">
                            <!-- Step 2 form fields here -->
                            <h3>Your Details</h3>
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="fname" class="form-label fw-semibold mb-1"
                                        style="font-size: 13px;">First Name:</label>
                                    <input type="text" class="form-control" id="fname" name="first_name">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="lname" class="form-label fw-semibold mb-1"
                                        style="font-size: 13px;">Last Name:</label>
                                    <input type="text" class="form-control" id="lname" name="last_name">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="email" class="form-label fw-semibold mb-1"
                                        style="font-size: 13px;">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="phoneNum" class="form-label fw-semibold mb-1"
                                        style="font-size: 13px;">Phone Number:</label>
                                    <input type="number" class="form-control" id="phoneNum" name="phone_number">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="city" class="form-label fw-semibold mb-1"
                                        style="font-size: 13px;">City:</label>
                                    <input type="text" class="form-control" id="city" name="city">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="state" class="form-label fw-semibold mb-1"
                                        style="font-size: 13px;">State:</label>
                                    <input type="text" class="form-control" id="state" name="state">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="room" class="form-label fw-semibold mb-1"
                                        style="font-size: 13px;">Room Number:</label>
                                    <input type="text" class="form-control" id="room_number" name="room_number">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="date" class="form-label fw-semibold mb-1"
                                        style="font-size: 13px;">Booking Date:</label>
                                    <input type="date" class="form-control" id="booking_date"
                                        name="booking_date">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="time" class="form-label fw-semibold mb-1"
                                        style="font-size: 13px;">Booking Time:</label>
                                    <input type="time" class="form-control" id="booking_time"
                                        name="booking_time">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="time" class="form-label fw-semibold mb-1"
                                        style="font-size: 13px;">Tower:</label>
                                    <select name="tower" class="form-control form-select">
                                        <option value="east_tower">East Tower</option>
                                        <option value="west_tower">West Tower</option>

                                    <select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="address" class="form-label fw-semibold mb-1"
                                        style="font-size: 13px;">Address:</label>
                                    <textarea class="form-control" rows="3" name="address"></textarea>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-primary prev-step">Previous</button>
                                <button type="button" class="btn btn-primary next-step">Next</button>
                            </div>
                        </div>
                        <div class="step step-3">
                            <!-- Step 3 form fields here -->
                            <h3>Card info & Final Step</h3>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="cardNum" class="form-label fw-semibold mb-1"
                                        style="font-size: 13px;">Name On Card</label>
                                    <input type="text" class="form-control" id="cardNum" name="name_on_card">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="expDate" class="form-label fw-semibold mb-1"
                                        style="font-size: 13px;">Card Number</label>
                                    <input type="text" class="form-control" id="card_number" name="card_number">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="expDate" class="form-label fw-semibold mb-1"
                                        style="font-size: 13px;">CVC</label>
                                    <input type="text" class="form-control" id="cvc" name="cvc">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="expDate" class="form-label fw-semibold mb-1"
                                        style="font-size: 13px;">Expiry Month</label>
                                    <input type="text" class="form-control" id="expiry_month"
                                        name="expiry_month">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="expDate" class="form-label fw-semibold mb-1"
                                        style="font-size: 13px;">Expiry Year</label>
                                    <input type="text" class="form-control" id="expiry_year" name="expiry_year">
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-primary prev-step">Previous</button>
                                <button type="button" class="btn btn-primary next-step">Next</button>
                            </div>
                        </div>
                        <div class="step step-4">
                            <!-- Step 3 form fields here -->
                            <h3>Review & Billing Summary</h3>
                            <div class="content-info mt-4">
                                <h5>Contact info</h5>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-1">Name</p>
                                    <p class="mb-1 review-name">-</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-1">Email</p>
                                    <p class="mb-1 review-email">-</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-1">Mobile Phone</p>
                                    <p class="mb-1 review-phone">-</p>
                                </div>
                            </div>

                            <div class="billing-info mt-4">
                                <h5>Your Booking</h5>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-1">Beach Set</p>
                                    <p class="mb-1 review-sets">-</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-1">Additional Seats</p>
                                    <p class="mb-1 review-seats">-</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-1">Additional Umbrella</p>
                                    <p class="mb-1 review-umbrella">-</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-1">Date</p>
                                    <p class="mb-1 review-date">-</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-1">Time Slot</p>
                                    <p class="mb-1 review-time">-</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-1">Room Number</p>
                                    <p class="mb-1 review-room">-</p>
                                </div>
                            </div>

                            <div class="billing-info my-4">
                                <h5>Billing Details</h5>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-1">Base Price (Sets)</p>
                                    <p class="mb-1 review-base">$0</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-1">Add-ons</p>
                                    <p class="mb-1 review-addons">$0</p>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <p class="fs-5 mb-1 fw-semibold">Subtotal</p>
                                    <p class="fs-5 mb-1 fw-semibold review-total">$0</p>
                                </div>
                            </div>
                            <input type="hidden" name="pricing_id" class="pricingId">
                            <input type="hidden" name="total_price" class="totalPrice">


                            <div class="alert alert-warning" style="font-size: 13px;">Please review your booking
                                details carefully before confirming. Once you submit, you’ll receive a confirmation
                                email with your slot details.</div>
                            <div class="text-end">
                                <button type="button" class="btn btn-primary prev-step">Previous</button>
                                <button type="button" class="btn btn-primary" onclick="reserve()">Confirm &
                                    Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- <div class="modal-footer">
                <button type="button" class="btn btn-primary next-step">Next</button>
            </div> -->
            </div>
        </div>
    </div>
    <!-- Modal End -->
    <footer class="pt-5 bg-black">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="footer-col">
                        <a href="index.html"><img src="{{ asset('userside_assets/assets/images/logo.png') }}"
                                class="footer-logo" width="200" alt="logo"></a>
                        <ul class="list-unstyled mt-4 d-flex justify-content-start gap-2">
                            <li>
                                <a href="#" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 640 640">
                                        <path
                                            d="M240 363.3L240 576L356 576L356 363.3L442.5 363.3L460.5 265.5L356 265.5L356 230.9C356 179.2 376.3 159.4 428.7 159.4C445 159.4 458.1 159.8 465.7 160.6L465.7 71.9C451.4 68 416.4 64 396.2 64C289.3 64 240 114.5 240 223.4L240 265.5L174 265.5L174 363.3L240 363.3z" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="#" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 640 640">
                                        <path
                                            d="M320.3 205C256.8 204.8 205.2 256.2 205 319.7C204.8 383.2 256.2 434.8 319.7 435C383.2 435.2 434.8 383.8 435 320.3C435.2 256.8 383.8 205.2 320.3 205zM319.7 245.4C360.9 245.2 394.4 278.5 394.6 319.7C394.8 360.9 361.5 394.4 320.3 394.6C279.1 394.8 245.6 361.5 245.4 320.3C245.2 279.1 278.5 245.6 319.7 245.4zM413.1 200.3C413.1 185.5 425.1 173.5 439.9 173.5C454.7 173.5 466.7 185.5 466.7 200.3C466.7 215.1 454.7 227.1 439.9 227.1C425.1 227.1 413.1 215.1 413.1 200.3zM542.8 227.5C541.1 191.6 532.9 159.8 506.6 133.6C480.4 107.4 448.6 99.2 412.7 97.4C375.7 95.3 264.8 95.3 227.8 97.4C192 99.1 160.2 107.3 133.9 133.5C107.6 159.7 99.5 191.5 97.7 227.4C95.6 264.4 95.6 375.3 97.7 412.3C99.4 448.2 107.6 480 133.9 506.2C160.2 532.4 191.9 540.6 227.8 542.4C264.8 544.5 375.7 544.5 412.7 542.4C448.6 540.7 480.4 532.5 506.6 506.2C532.8 480 541 448.2 542.8 412.3C544.9 375.3 544.9 264.5 542.8 227.5zM495 452C487.2 471.6 472.1 486.7 452.4 494.6C422.9 506.3 352.9 503.6 320.3 503.6C287.7 503.6 217.6 506.2 188.2 494.6C168.6 486.8 153.5 471.7 145.6 452C133.9 422.5 136.6 352.5 136.6 319.9C136.6 287.3 134 217.2 145.6 187.8C153.4 168.2 168.5 153.1 188.2 145.2C217.7 133.5 287.7 136.2 320.3 136.2C352.9 136.2 423 133.6 452.4 145.2C472 153 487.1 168.1 495 187.8C506.7 217.3 504 287.3 504 319.9C504 352.5 506.7 422.6 495 452z" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="#" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 640 640">
                                        <path
                                            d="M544.5 273.9C500.5 274 457.5 260.3 421.7 234.7L421.7 413.4C421.7 446.5 411.6 478.8 392.7 506C373.8 533.2 347.1 554 316.1 565.6C285.1 577.2 251.3 579.1 219.2 570.9C187.1 562.7 158.3 545 136.5 520.1C114.7 495.2 101.2 464.1 97.5 431.2C93.8 398.3 100.4 365.1 116.1 336C131.8 306.9 156.1 283.3 185.7 268.3C215.3 253.3 248.6 247.8 281.4 252.3L281.4 342.2C266.4 337.5 250.3 337.6 235.4 342.6C220.5 347.6 207.5 357.2 198.4 369.9C189.3 382.6 184.4 398 184.5 413.8C184.6 429.6 189.7 444.8 199 457.5C208.3 470.2 221.4 479.6 236.4 484.4C251.4 489.2 267.5 489.2 282.4 484.3C297.3 479.4 310.4 469.9 319.6 457.2C328.8 444.5 333.8 429.1 333.8 413.4L333.8 64L421.8 64C421.7 71.4 422.4 78.9 423.7 86.2C426.8 102.5 433.1 118.1 442.4 131.9C451.7 145.7 463.7 157.5 477.6 166.5C497.5 179.6 520.8 186.6 544.6 186.6L544.6 274z" />
                                    </svg>
                                </a>
                            </li>
                        </ul>
                        <div class="text-start w-75">
                            <h4 class="fs-5 fw-medium mb-3 col-title">Newsletter</h4>
                            <form action="">
                                <div class="">
                                    <input type="email"
                                        class="form-control bg-transparent text-white p-2 mb-3 subscribe-input"
                                        name="newsletter_email" placeholder="Enter your email...">
                                    <input type="button" class="btn btn-primary w-100 text-center" id="subscribeBtn"
                                        value="Subscribe">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="footer-col">
                        <h3 class="col-title fs-3 fw-semibold mb-3">Contact</h3>
                        <p class="address fw-normal lh-sm">It is a long established fact that a reader will be
                            distracted by the readable content of a page when looking at its layout.</p>
                        <div class="contact-block-item mt-4">
                            <h4 class="fs-5 fw-medium mb-3 col-title">For Call/Email</h4>
                            <ul class="list-unstyled">
                                <li class="d-flex align-items-center gap-1 pb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 640 640">
                                        <path
                                            d="M176.8 74.9C204.1 65.8 233.8 78.8 245.7 104.9L285.4 192.2C296 215.6 289.4 243.2 269.4 259.3L245.2 278.6C270.7 328.6 310.7 370 359.6 397.4L380.8 370.8C396.9 350.7 424.5 344.1 447.9 354.8L535.2 394.5C561.4 406.4 574.3 436.1 565.2 463.4C544.5 525.7 481.5 579.6 404.3 566C230.6 535.4 104.7 409.5 74.1 235.8C60.5 158.6 114.5 95.7 176.7 74.9zM202 124.8C200.3 121 196 119.1 192 120.4C146.8 135.5 112.9 179 121.5 227.4C148.6 381.2 258.9 491.6 412.7 518.7C461.1 527.2 504.6 493.4 519.7 448.2C521 444.2 519.1 439.9 515.3 438.2L428 398.4C424.6 396.9 420.6 397.8 418.3 400.7L384.8 442.6C377.8 451.3 365.8 454.1 355.8 449.3C283.3 414.9 225.3 355 193.4 281.1C189.1 271.2 192 259.6 200.4 252.9L239.3 221.8C242.2 219.5 243.2 215.5 241.6 212.1L201.9 124.7z" />
                                    </svg>
                                    <a href="tel:+10000000000" class="text-decoration-none">+10000000000</a>
                                </li>
                                <li class="d-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 640 640">
                                        <path
                                            d="M125.4 128C91.5 128 64 155.5 64 189.4C64 190.3 64 191.1 64.1 192L64 192L64 448C64 483.3 92.7 512 128 512L512 512C547.3 512 576 483.3 576 448L576 192L575.9 192C575.9 191.1 576 190.3 576 189.4C576 155.5 548.5 128 514.6 128L125.4 128zM528 256.3L528 448C528 456.8 520.8 464 512 464L128 464C119.2 464 112 456.8 112 448L112 256.3L266.8 373.7C298.2 397.6 341.7 397.6 373.2 373.7L528 256.3zM112 189.4C112 182 118 176 125.4 176L514.6 176C522 176 528 182 528 189.4C528 193.6 526 197.6 522.7 200.1L344.2 335.5C329.9 346.3 310.1 346.3 295.8 335.5L117.3 200.1C114 197.6 112 193.6 112 189.4z" />
                                    </svg>
                                    <a href="mailto:info@example.com"
                                        class="text-decoration-none">info@example.com</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="footer-col">
                        <h3 class="col-title fs-3 fw-semibold mb-3">Opening Hours</h3>
                        <p class="address fw-normal lh-sm">It is a long established fact that a reader will be
                            distracted by the readable content of a page when looking at its layout.</p>
                        <div class="contact-block-item mt-4">
                            <h4 class="fs-5 fw-medium mb-3 col-title">Availability</h4>
                            <ul class="list-unstyled">
                                <li class="d-flex align-items-center gap-1 pb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 640 640">
                                        <path
                                            d="M256 120L256 160L384 160L384 120C384 115.6 380.4 112 376 112L264 112C259.6 112 256 115.6 256 120zM208 160L208 120C208 89.1 233.1 64 264 64L376 64C406.9 64 432 89.1 432 120L432 160L512 160C547.3 160 576 188.7 576 224L576 289.4C560.9 282.5 544.8 277.5 528 274.6L528 223.9C528 215.1 520.8 207.9 512 207.9L128 207.9C119.2 207.9 112 215.1 112 223.9L112 319.9L369 319.9C340.7 344.9 319.8 378.2 310 415.9L288 415.9C270.3 415.9 256 401.6 256 383.9L256 367.9L112 367.9L112 479.9C112 488.7 119.2 495.9 128 495.9L306.7 495.9C309.5 512.7 314.5 528.8 321.5 543.9L128 544C92.7 544 64 515.3 64 480L64 224C64 188.7 92.7 160 128 160L208 160zM352 464C352 384.5 416.5 320 496 320C575.5 320 640 384.5 640 464C640 543.5 575.5 608 496 608C416.5 608 352 543.5 352 464zM496 384C487.2 384 480 391.2 480 400L480 464C480 472.8 487.2 480 496 480L544 480C552.8 480 560 472.8 560 464C560 455.2 552.8 448 544 448L512 448L512 400C512 391.2 504.8 384 496 384z" />
                                    </svg>
                                    <p class="mb-0">
                                        <span>9 AM</span> to <span>6 PM</span>
                                    </p>
                                </li>
                                <li class="d-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 640 640">
                                        <path
                                            d="M216 64C229.3 64 240 74.7 240 88L240 128L400 128L400 88C400 74.7 410.7 64 424 64C437.3 64 448 74.7 448 88L448 128L480 128C515.3 128 544 156.7 544 192L544 480C544 515.3 515.3 544 480 544L160 544C124.7 544 96 515.3 96 480L96 192C96 156.7 124.7 128 160 128L192 128L192 88C192 74.7 202.7 64 216 64zM480 496C488.8 496 496 488.8 496 480L496 416L408 416L408 496L480 496zM496 368L496 288L408 288L408 368L496 368zM360 368L360 288L280 288L280 368L360 368zM232 368L232 288L144 288L144 368L232 368zM144 416L144 480C144 488.8 151.2 496 160 496L232 496L232 416L144 416zM280 416L280 496L360 496L360 416L280 416zM216 176L160 176C151.2 176 144 183.2 144 192L144 240L496 240L496 192C496 183.2 488.8 176 480 176L216 176z" />
                                    </svg>
                                    <p class="mb-0">
                                        <span>Monday</span> to <span>Sunday</span>
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <hr>
        <div class="container">
            <div class="row copyright-row">
                <div class="col-8">
                    <p class="fw-light lh-sm">Copyright © 2025 Beach Sitting. All rights reserved.</p>
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled d-flex align-items-center justify-content-end gap-2">
                        <li><a href="#" class="text-decoration-none">Privacy Policy</a></li>
                        <li><a href="#" class="text-decoration-none">Terms & Conditions</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('userside_assets/assets/js/card-modal.js') }}"></script>
    <script src="{{ asset('userside_assets/assets/js/custom.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function bookNow(itemType) {
            const modalTitle = document.getElementById('exampleModalLabel');
            modalTitle.textContent = `Book Now - ${itemType.replace(/_/g, ' ').toUpperCase()}`;
            const form = document.getElementById('multi-step-form');
            form.reset();
            displayStep(1);
            const myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
            myModal.show();
            document.getElementById('category_selected').value = itemType;
            if (itemType === 'chair_with_umbrella') {
                $('.umbrellasInputDiv').removeClass('d-none');
            } else {
                $('.umbrellasInputDiv').addClass('d-none');
            }
        }

        function reserve() {
            var form = $('#multi-step-form')[0];
            var formData = new FormData(form);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('user.reserve.booking.login') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Reservation Successful!',
                            text: response.message ?? 'Your booking has been confirmed.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.reload();
                        });
                    } else if (response.status === 'error') {
                        // For backend custom errors (like tenant insufficient balance, seats unavailable, etc.)
                        Swal.fire({
                            icon: 'error',
                            title: 'Reservation Failed',
                            text: response.message ??
                                'Unable to process your booking. Please try again.'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Remove previous validation errors
                    $('#multi-step-form .text-danger').remove();

                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        // Laravel validation errors — display below inputs
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function(fieldName, messages) {
                            const input = $('#multi-step-form [name="' + fieldName + '"]');
                            if (input.length > 0) {
                                input.after('<small class="text-danger">' + messages[0] + '</small>');
                            }
                        });
                    } else if (xhr.status === 401) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Credentials',
                            text: 'The email or password you entered is incorrect.'
                        });
                    } else if (xhr.status === 403) {
                        // 👇 Extract counts if present
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
                    } else if (xhr.responseJSON?.message) {
                        // Other custom error messages
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON.message
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: 'Something went wrong. Please try again later.'
                        });
                    }
                }
            });
        }


        function signIn() {
            var form = $('#loginForm')[0];
            var formData = new FormData(form);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('user.login.process') }}', // Replace with your login route
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Login Successful!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Remove old validation messages
                    $('#loginForm .text-danger').remove();

                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        // Laravel validation errors (e.g., missing unique_code or login_password)
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function(fieldName, messages) {
                            const input = $('#loginForm [name="' + fieldName + '"]');
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Simple mapping between form fields and review section elements ---
            const fieldMap = {
                fname: ".review-name",
                email: ".review-email",
                phoneNum: ".review-phone",
                how_many_set: ".review-sets",
                addon_seats: ".review-seats",
                addon_umbrella: ".review-umbrella",
                booking_date: ".review-date",
                booking_time: ".review-time",
                room_number: ".review-room"
            };

            // Attach event listeners to each input
            Object.keys(fieldMap).forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    ['keyup', 'change'].forEach(evt => {
                        input.addEventListener(evt, () => {
                            document.querySelector(fieldMap[id]).innerText = input.value ||
                                '-';
                            calculateTotal();
                        });
                    });
                }
            });

            // --- Fetch pricing from backend ---
            let pricing = {
                base_set: 65,
                seat: 10,
                umbrella: 5
            }; // default
            fetch('/get-active-pricing')
                .then(res => res.json())
                .then(data => pricing = data)
                .catch(err => console.error(err));

            // --- Calculate subtotal dynamically ---
            function calculateTotal() {
                const sets = parseInt(document.getElementById('how_many_set').value) || 0;
                const addonSeats = parseInt(document.getElementById('addon_seats').value) || 0;
                const addonUmbrella = parseInt(document.getElementById('addon_umbrella').value) || 0;

                const basePrice = sets * pricing.base_set;
                const addonPrice = addonSeats * pricing.seat + addonUmbrella * pricing.umbrella;
                const subtotal = basePrice + addonPrice;
                $('.pricingId').val(pricing.priceId);
                $('.totalPrice').val(subtotal);

                document.querySelector('.review-base').innerText = `$${basePrice}`;
                document.querySelector('.review-addons').innerText = `$${addonPrice}`;
                document.querySelector('.review-total').innerText = `$${subtotal}`;
            }
        });
    </script>

</body>

</html>
