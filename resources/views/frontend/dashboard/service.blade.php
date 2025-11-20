@extends('frontend.layout.index')

@section('content')

<div class="container my-5">
    <div class="container my-5">
        <h1 class="text-center mb-4" style="color: #2E4A5B;">Our Services</h1>
    
        <p class="text-center mb-5" >
            Blood Management System (BMS) provides essential services to connect donors, patients, and blood banks efficiently. Helping save lives with every drop!
        </p>
    
        <div class="row g-4">
            <!-- Donor Registration -->
            <div class="col-md-6">
                <div class="card text-white" style="background-color: #dc3545; border-radius: 15px;">
                    <div class="card-body">
                        <h5 class="card-title">Donor Registration</h5>
                        <p class="card-text" style="color: white;">Easily register as a blood donor and manage your profile to help save lives.</p>
                    </div>
                </div>
            </div>
    
            <!-- Blood Request Submission -->
            <div class="col-md-6">
                <div class="card text-white" style="background-color: #2E4A5B; border-radius: 15px;">
                    <div class="card-body">
                        <h5 class="card-title">Blood Request Submission</h5>
                        <p class="card-text" style="color: white;">Submit blood requests for patients in need and track their status in real-time.</p>
                    </div>
                </div>
            </div>
    
            <!-- Find Nearest Blood Bank -->
            <div class="col-md-6">
                <div class="card text-white" style="background-color: #dc3545; border-radius: 15px;">
                    <div class="card-body">
                        <h5 class="card-title">Find Nearest Blood Bank</h5>
                        <p class="card-text" style="color: white;">Locate nearby blood banks quickly using your current location for urgent needs.</p>
                    </div>
                </div>
            </div>
    
            <!-- Donation Awareness Campaigns -->
            <div class="col-md-6">
                <div class="card text-white" style="background-color: #2E4A5B; border-radius: 15px;">
                    <div class="card-body">
                        <h5 class="card-title">Donation Awareness Campaigns</h5>
                        <p class="card-text"style="color: white;">Stay informed about blood donation drives and awareness campaigns in your community.</p>
                    </div>
                </div>
            </div>
    
            <!-- Blood Bank Inventory Management -->
            <div class="col-md-6">
                <div class="card text-white" style="background-color: #dc3545; border-radius: 15px;">
                    <div class="card-body">
                        <h5 class="card-title">Blood Bank Inventory Management</h5>
                        <p class="card-text" style="color: white;">Blood banks can efficiently manage and monitor their blood stock to ensure availability.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection