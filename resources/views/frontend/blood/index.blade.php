@extends('frontend.layout.index')

@section('content')
    <!-- Header -->
    <div class="bg-danger text-white p-3 d-flex align-items-center">
        <img src="https://eraktkosh.in/logo.png" alt="logo" height="40" class="me-3">
        <div>
            <h4 class="mb-0">Blood Management System</h4>
            <small>Ministry of Health and Family Welfare</small>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container my-4">
        <div class="bg-white rounded shadow-sm border">

            <!-- Full-width dark header inside the card -->
            <div style="background-color: #2e4a5b;" class="text-white p-3 rounded-top">

                <h5 class="mb-0">Blood Availability</h5>
            </div>

            <!-- Card content padding -->
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                    <!-- Left: Results count -->
                    <p class="mb-0" id="results-count">{{ $blood->count() }} Results found</p>

                    <!-- Right: Search Form -->
                    <div class="d-flex flex-wrap gap-2">


                        <!-- Location Modal Trigger -->
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                            data-bs-target="#locationModal">
                            Select Location
                        </button>

                        <!-- Search Button -->
                        <button class="btn btn-danger reset">
                            Reset
                        </button>
                    </div>
                </div>

                {{--  MODAL --}}

                <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content border-0 rounded">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="locationModalLabel">Select Location</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="locationForm">
                                <div class="modal-body">

                                    <div class="mb-3">
                                        <label for="district" class="form-label">District</label>
                                        <select class="form-control" name="district" id="district" required>
                                            <option value="">Select District</option>
                                            <option value="Kathmandu">Kathmandu</option>
                                            <option value="Lalitpur">Lalitpur</option>
                                            <option value="Bhaktapur">Bhaktapur</option>
                                            <option value="Chitwan">Chitwan</option>
                                            <option value="Makwanpur">Makwanpur</option>
                                            <option value="Pokhara">Pokhara</option>
                                            <option value="Kavrepalanchok">Kavrepalanchok</option>
                                            <option value="Morang">Morang</option>
                                            <option value="Jhapa">Jhapa</option>
                                            <option value="Biratnagar">Morang</option>
                                            <option value="Damak">Jhapa</option>
                                            <option value="Banke">Banke</option>
                                            <option value="Rupandehi">Rupandehi</option>
                                            <option value="Kailali">Kailali</option>
                                            <option value="Dhangadhi">Kailali</option>
                                            <option value="Saptari">Saptari</option>
                                            <option value="Janakpur">Dhanusha</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="state" class="form-label">Province</label>
                                        <select class="form-control" name="state" id="state" required>
                                            <option value="">Select Province</option>
                                            <option value="Bagmati">Bagmati</option>
                                            <option value="Gandaki">Gandaki</option>
                                            <option value="Koshi">Koshi</option>
                                            <option value="Lumbini">Lumbini</option>
                                            <option value="Sudurpashchim">Sudurpashchim</option>
                                            <option value="Madhesh">Madhesh</option>
                                        </select>
                                    </div>


                                    <!-- Blood Group Dropdown -->
                                    <div class="mb-3">
                                        <label for="state" class="form-label">Select Blood Group</label>
                                        <select class="form-select text-muted" name="group"
                                            aria-label="Select Blood Group" required>
                                            <option selected disabled>Select Blood Group</option>
                                            @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $group)
                                                <option value="{{ $group }}">{{ $group }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="location" class="form-label">Enter your location</label>
                                        <input type="text" class="form-control" id="location" name="location"
                                            placeholder="City, district, or address" />

                                    </div>


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">cancel</button>
                                    <button type="submit" class="btn btn-danger">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row g-3" id="blood-results">
                    @foreach ($blood->take(10) as $bloods)
                        <!-- Blood Center Card 1 -->
                        <div class="col-md-6">
                            <div class="card border-danger">
                                <div class="card-body">
                                    <small class="text-muted">Updated on Jun 4, 2025, 9:31:37 AM</small>
                                    @foreach ($bloods->bloodBanks as $bloodBank)
                                        <h6 class="card-title mt-2 text-danger">
                                            {{ $bloods->name }}
                                            ({{ $bloodBank->pivot->quantity }})
                                        </h6>

                                        <div class="my-2">
                                            <span class="badge rounded-pill bg-danger-subtle text-danger">
                                                {{ $bloodBank->name }}
                                                <span
                                                    class="badge bg-light text-danger border border-danger ms-1">Govt.</span>
                                            </span>
                                        </div>
                                    @endforeach
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('frontblood.detail', $bloods->id) }}"
                                            class="btn btn-outline-danger btn-sm">Details</a>
                                        <button class="btn btn-sm text-white" style="background-color: #2e4a5b;">
                                            ✈️ 0.00 kms
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Add more cards similarly -->
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#locationForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();
                $('#locationModal').modal('hide');
                $.ajax({
                    method: 'GET',
                    url: '/blood/search/data',
                    data: formData,

                    success: function(response) {
                        // console.log(response);
                        // location.reload();
                        if (response.status && Array.isArray(response.data)) {
                            $('#results-count').text(response.data.length + ' Results found');
                            let html = '';

                            // Loop through each Blood Bank in response.data
                            response.data.forEach(function(bloodBank) {
                                html += `
                                    <div class="col-md-6">
                                        <div class="card border-danger">
                                        <div class="card-body">
                                            <small class="text-muted">Updated on Jul 28, 2025, 9:31:37 AM</small>
                                    `;

                                // Loop through each Blood associated with this Blood Bank
                                if (Array.isArray(bloodBank.bloods)) {
                                    bloodBank.bloods.forEach(function(blood, index) {
                                        html += `
                                            <h6 class="card-title mt-2 text-danger d-flex justify-content-between align-items-center">
                                                <span>${blood.name} (${blood.pivot?.quantity ?? 0})</span>
                                                
                                            </h6>
                                            `;

                                        if (index === 0) {
                                            bloodIdForDetail = blood.id;
                                        }
                                    });
                                }

                                // Blood Bank name + Govt badge below blood entries
                                html += `
                                            <div class="my-2">
                                            <span class="badge rounded-pill bg-danger-subtle text-danger">
                                                ${bloodBank.name}
                                                <span class="badge bg-light text-danger border border-danger ms-1">Govt.</span>
                                            </span>
                                            </div>
                                    `;

                                // Action buttons at the bottom
                                html += `
                                            <div class="d-flex justify-content-between">
                                            <a href="/blood/detail/${bloodIdForDetail}" class="btn btn-outline-danger btn-sm">Details</a>
                                            <button class="btn btn-sm text-white" style="background-color: #2e4a5b;">
                                                ✈️ ${bloodBank.distance.toFixed(2)} kms
                                            </button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    `;

                            });

                            // Replace the contents of the container '.row.g-3' with generated HTML
                            $('.row.g-3').html(html);

                        } else {
                            // If no data or invalid format, show this friendly message
                            $('.row.g-3').html(
                                '<p>No results found or invalid data format.</p>');
                        }
                    },
                });

            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.reset').on('click', function() {
                location.reload();
            });
        });
    </script>

    {{-- <script>
        $(document).ready(function() {
            $('.form-select').on('change', function() {
                let selectedGroup = $(this).val();
                console.log('Selected Blood Group:', selectedGroup);
                $.ajax({
                    method: 'GET',
                    url: '/blood/search/data',
                    data: {
                        group: selectedGroup
                    },
                    success: function(response) {
                        console.log(response.data);
                        let resultsContainer = $('#blood-results');
                        resultsContainer.empty(); // Clear old results

                        $('#results-count').text(response.data.length + ' Results found');


                        if (response.data.length === 0) {
                            $('#results-count').text(response.data.length + ' Results found');
                            resultsContainer.html(
                                '<p class="text-center text-muted">No blood banks found for the selected group.</p>'

                            );
                            return;
                        }

                        // Loop through blood types
                        response.data.forEach(function(blood) {
                            // console.log(response.data);
                            // Loop through each associated blood bank
                            blood.blood_banks.forEach(function(bank) {
                                // console.log('blood'+response.data);
                                let cardHtml = `
                                <div class="col-md-6">
                                    <div class="card border-danger">
                                        <div class="card-body">
                                         
                                            <h6 class="card-title mt-2 text-danger">
                                                    ${blood.name} (${bank.pivot.quantity})
                                               
                                            </h6>
                                            <div class="my-2">
                                                <span class="badge rounded-pill bg-danger-subtle text-danger">
                                                 ${bank.name}
                                                <span class="badge bg-light text-danger border border-danger ms-1">Govt.</span>
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <a href="/blood/detail/${blood.id}" class="btn btn-outline-danger btn-sm">Details</a>
                                                <button class="btn btn-sm text-white" style="background-color: #2e4a5b;">
                                                    ✈️ 8771.03 kms
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                                resultsContainer.append(cardHtml);
                            });
                        });
                    },

                });
            });
        });
    </script> --}}
@endsection
