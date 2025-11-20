@extends('frontend.layout.index')
@section('content')
    <div class="container py-4">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('frontblood.index') }}">Blood</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $blood->name }}</li>
            </ol>
        </nav>
        <!-- Blood Bank Image -->


                {{-- <div class="row mb-4">
                    <div class="col-12">
                        <img src="{{ asset('images/bloodbank/default.jpg') }}" class="img-fluid w-100 rounded"
                            style="height:600px; width:100px;" alt="Default Blood Bank Image">
                    </div>
                </div> --}}
            
        <!-- Main Content -->

        <div class="row g-4">

            <!-- Left Column -->
            <div class="col-md-8">

                <!-- Blood Group Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="card-title text-danger">
                            {{ $blood->name }}
                            @foreach ($blood->bloodBanks as $bloodBank)
                                <span class="badge bg-danger-subtle text-danger ms-2">
                                    {{ $bloodBank->pivot->quantity }} Units
                                </span>
                            @endforeach
                        </h3>
                    </div>
                </div>
            
                <!-- Blood Description -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-dark">Description</h5>
                        <p class="card-text text-muted">
                            {!! $blood->description !!}
                        </p>
                    </div>
                </div>
            
                <!-- Blood Bank Info -->
                @foreach ($blood->bloodBanks as $bloodBank)
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-dark">Blood Bank Info</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Name:</strong> {{ $bloodBank->name }}</li>
                                <li class="list-group-item"><strong>Address:</strong> {{ $bloodBank->city }},{{ $bloodBank->district }},{{ $bloodBank->state }}</li>
                                <li class="list-group-item">
                                    <strong>Status:</strong>
                                    <span class="badge {{ $bloodBank->status ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $bloodBank->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
            

            <!-- Right Column -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title text-danger fw-bold">Upcoming Blood Donation Camps</h5>
                        <ul class="list-unstyled mt-3">
                            @foreach($camps as $camp)
                                <li class="mb-2"><a href="{{ route('frontbloodcamp.index',$camp->id) }}" class="text-decoration-none text-dark">🩸 {{$camp->name  }}
                                        - {{ $camp->date }}</a></li>
                                        @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-4">
       
        </div> --}}
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
