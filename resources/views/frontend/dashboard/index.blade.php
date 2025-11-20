@extends('frontend.layout.index')

@section('content')


    <!-- Hero Section -->
    <section class="hero">
        <div class="overlay">
            <div class="container hero-content">
                <h1>There is no <br><span>Substitute for blood</span></h1>
                <p>Your blood can give someone a second chance at life.
                    Donate today and be a hero to those in need. <br>
                    Every donation saves lives, supports surgeries, and aids patients battling serious illnesses.
                    Join us in making a meaningful impact with just one simple act.</p>
                <div class="hero-buttons">
                    <a href="#testimonial" class="btn signup">Experience</a>
                    <a href="{{ route('about.index') }}" class="btn login">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery -->
    <section class="py-5 bg-light">

        <div class="container">
            <h3 class="text-danger mb-4 fw-bold">Past Blood Donation Camps</h3>
            <div class="row">
                <!-- Gallery Section -->
                <div class="col-md-8 mb-4 mb-md-0">
                    <div class="row g-3">
                        @foreach($pastCamps->take(3) as $camp)
                        <div class="col-md-4">
                            <div class="card shadow-sm custom-hover">
                                <a href="{{ route('frontbloodcamp.index',$camp->id) }}" class="text-decoration-none ">
                                    <img src="{{ asset('images/camps/'.$camp->image) }}" class="card-img-top rounded"
                                        alt="Gallery 1" style="height:200px;width=100px">
                                    <div class="card-body">
                                        <p class="card-text">{!! Str::limit(strip_tags($camp->description,50)) !!}</p>
                                        @foreach($camp->bloodBanks as $bloodBank)
                                        <small class="text-muted">{{ $bloodBank->name }}</small>
                                        @endforeach
                                    </div>
                                </a>
                            </div>
                        </div>
                     @endforeach
                    </div>
                </div>

                <!-- Upcoming Camps Section -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-danger fw-bold">Upcoming Blood Donation Camps</h5>
                            <ul class="list-unstyled mt-3">
                                @foreach($camps->take(8) as $camp)
                                <li class="mb-2"><a href="{{ route('frontbloodcamp.index',$camp->id) }}" class="text-dark text-decoration-none">🩸 {{$camp->name  }}
                                        - {{ $camp->date }}</a></li>
                                        @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <!-- Gallery Section -->
                <div class="col-md-8 mb-4 mb-md-0">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <!-- Card 1 -->
                            <div class="card shadow-sm p-3 d-flex flex-row align-items-center justify-content-between mb-3">
                                <!-- Icon -->
                                <div class="me-3">
                                    <i class="bi bi-droplet-fill text-danger fs-1"></i>
                                </div>
                                <!-- Text -->
                                <div class="flex-grow-1 me-3">
                                    <p class="mb-0 fw-semibold">Easily check the availability of blood by selecting your
                                        blood group, component, and location. You can also explore a nationwide list of
                                        registered blood banks by simply choosing your state and district.</p>
                                </div>
                                <!-- Button -->
                                <div>
                                    <a href="{{ route('frontblood.index') }}" class="btn btn-danger btn-sm">Search Blood</a>
                                </div>
                            </div>

                            <!-- Card 2 -->
                            <div class="card shadow-sm p-3 d-flex flex-row align-items-center justify-content-between mb-3">
                                <!-- Icon --> <i class="fa-solid fa-hands-holding-heart text-danger fs-1"></i>
                                <div class="me-3">
                                    <i class="bi bi-hospital-fill  fs-1"></i>
                                </div>
                                <!-- Text -->
                                <div class="flex-grow-1 me-3">
                                    <p class="mb-0 fw-semibold">Find licensed blood banks near you by selecting your state
                                        and district. Get accurate and up-to-date details to access blood when it’s needed
                                        the most.</p>
                                </div>
                                <!-- Button -->
                                <div>
                                    <a href="{{ route('frontbloodbank.index') }}" class="btn btn-danger btn-sm">Search
                                        Bloodbank</a>
                                </div>
                            </div>


                            <!-- Card 3 -->
                            {{-- <div class="card shadow-sm p-3 d-flex flex-row align-items-center justify-content-between mb-3">
                                <!-- Icon -->
                                <div class="me-3">
                                    <i class="bi bi-droplet-fill text-danger fs-1"></i>
                                </div>
                                <!-- Text -->
                                <div class="flex-grow-1 me-3">
                                    <p class="mb-0 fw-semibold">Easily check the availability of blood by selecting your
                                        blood group, component, and location. You can also explore a nationwide list of
                                        registered blood banks by simply choosing your state and district.</p>
                                </div>
                                <!-- Button -->
                                <div>
                                    <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#locationModal">My Donation</a>
                                </div> --}}

                                
                                {{-- MODAL --}}

                                {{-- <div class="modal fade" id="locationModalDonor" tabindex="-1"
                                    aria-labelledby="locationModalDonorLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content border-0 rounded">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="locationModalDonorLabel">Donation Detail</h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form id="locationForm">
                                                @csrf
                                                <div class="modal-body">

                                                    <div class="mb-3">
                                                        <label for="district" class="form-label">Username</label>
                                                        <input type="text" class="form-control" name="username"
                                                            id="district" placeholder="Enter username">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="state" class="form-label">password</label>
                                                        <input type="text" class="form-control" name="password"
                                                            id="state" placeholder="Enter password">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                        data-bs-dismiss="modal">cancel</button>
                                                    <button type="submit" class="btn btn-danger">Login</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> --}}

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Grey Strip -->
    <div class="grey-strip py-5" id="testimonial" style="background-color: #6c757d;">
        <div class="container">
            <div class="row justify-content-center g-4">

                <!-- Testimonial 1 -->
                <div class="col-md-4">
                    <div class="card bg-white text-dark shadow text-center">
                        <div class="card-body">
                            <img src="{{ asset('images/testimonial/client1.jpg') }}" alt="Client Name 1"
                                class="rounded-circle mb-3 mx-auto d-block"
                                style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #b30000;">
                            <p class="card-text fst-italic">
                                “Working with your team has been an absolute pleasure. Their dedication and professionalism
                                helped us save countless lives through blood donation campaigns.”
                            </p>
                            <h5 class="card-title mt-3 mb-0">— Aniket Mandal</h5>
                            <small class="text-muted">CEO, LifeSaver Org</small>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="col-md-4">
                    <div class="card bg-white text-dark shadow text-center">
                        <div class="card-body">
                            <img src="{{ asset('images/testimonial/client2.jpeg') }}" alt="Client Name 2"
                                class="rounded-circle mb-3 mx-auto d-block"
                                style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #b30000;">
                            <p class="card-text fst-italic ">
                                “Working with your team has been an absolute pleasure. Their dedication and professionalism
                                helped us save countless lives through blood donation campaigns.”
                            </p>
                            <h5 class="card-title mt-3 mb-0">— Linda Subedi</h5>
                            <small class="text-muted">Community Leader</small>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="col-md-4">
                    <div class="card bg-white text-dark shadow text-center">
                        <div class="card-body">
                            <img src="{{ asset('images/testimonial/jenish.jpeg') }}" alt="Client Name 3"
                                class="rounded-circle mb-3 mx-auto d-block"
                                style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #b30000;">
                            <p class="card-text fst-italic">
                                “Working with your team has been an absolute pleasure. Their dedication and professionalism
                                helped us save countless lives through blood donation campaigns.” </p>
                            <h5 class="card-title mt-3 mb-0">— Jenish Maharjan</h5>
                            <small class="text-muted">Volunteer</small>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif
</script>



