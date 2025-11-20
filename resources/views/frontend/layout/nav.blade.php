<header class="header">
    <div class="container nav">
        <div class="logo">
            <a href="{{ route('frontdashboard.index') }}" class="text-decoration-none fw-bold">
                <img src="{{ asset('/images/Front/bloodlogo.png') }}" style="height:150px; width:auto;">
            </a>
        </div>
        <nav>
            <ul class="nav-links list-unstyled d-flex gap-4 align-items-center m-0">
                <li><a href="{{ route('frontdashboard.index') }}" class="nav-link px-2 link-dark">HOME</a></li>
                <li><a href="{{ route('about.index') }}" class="nav-link px-2 link-dark">ABOUT US</a></li>
                <li><a href="{{ route('frontdashboard.services') }}" class="nav-link px-2 link-dark">SERVICES</a></li>
                <li><a href="#" class="nav-link px-2 link-dark">CONTACT US</a></li>
                <li>
                    <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#locationModalDonor">My Donation</a>
                </li>
            </ul>
        </nav>
    </div>
</header>
