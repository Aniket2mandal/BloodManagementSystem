<header class="header">
    <div class="container nav">

        <div class="logo">
            <a href="{{ route('frontdashboard.index') }}" class="text-decoration-none fw-bold">
                <img src="{{ asset('/images/Front/bloodlogo.png') }}" style="height:150px; width:auto;">
            </a>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="{{route('frontdashboard.index')}}">HOME</a></li>
                <li><a href="{{ route('about.index') }}">ABOUT US</a></li>
                <li><a href="#">SERVICES</a></li>
                <li><a href="#">CONTACT US</a></li>
            </ul>
        </nav>
    </div>
</header>
