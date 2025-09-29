@extends('tenant.layouts.master')
@section('main-content')
<div class="main w-100 pt-4 pe-4">

                <div class="container">
                    <div class="d-flex justify-content-between mb-4">
                        <h1 class="mb-0">Add New User</h1>
                    </div>
                    <div class="row">
                    <div class="col-6 mb-3">
                        <label for="fname" class="form-label">First name</label>
                        <input type="text" class="form-control" id="fname" name="fname">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="lname" class="form-label">Last name</label>
                        <input type="text" class="form-control" id="lname" name="lname">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="user_email" class="form-label ">Email</label>
                        <input type="email" class="form-control" id="user_email" name="user_email">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="user_phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="user_phone" name="user_phone">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="user_password" class="form-label">Address</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>
                    <div class="col-12">
                        <input type="submit" class="btn btn-lg btn-primary" id="user_profile_btn" value="Add">
                    </div>
                </div>
                </div>
            </div>
@endsection
