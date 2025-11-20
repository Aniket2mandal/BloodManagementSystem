<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Blood Donation</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-THf9MWkPfS0LFGe+ym6XsIYZdYEG9w2T+KbX2Yu2HzmcDdQkY1ZaDCjz3h9fAdLkZMkXHYHTIfn64U5yTw3VxA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/front/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/front/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/front/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/front/about.css') }}">
    <style>
        .nav-link:hover {
            color: white !important;
            background-color: #dc3545;
            text-decoration: none;
            border-radius: 5px;
            padding: 5px 10px;
            transition: all 0.3s ease-in-out;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    @include('frontend.layout.nav')



    @yield('content')

    <div class="modal fade" id="locationModalDonor" tabindex="-1" aria-labelledby="locationModalDonorLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 rounded">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="locationModalDonorLabel">Donation Detail</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="locationFormDonor">
                    @csrf
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="district" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" id="district"
                                placeholder="Enter username">
                        </div>
                        <div class="mb-3">
                            <label for="state" class="form-label">password</label>
                            <input type="text" class="form-control" name="password" id="state"
                                placeholder="Enter password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">cancel</button>
                        <button type="submit" class="btn btn-danger">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

   
    <!-- Footer -->
    @include('frontend.layout.footer')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#locationFormDonor').on('submit', function(e) {
                e.preventDefault();
    
                let formData = $(this).serialize();
                console.log(formData);
                $('#locationModalDonor').modal('hide');
                $.ajax({
                    method: 'POST',
                    url: '/donor/login',
                    data: formData,
    
                    success: function(response) {
                        window.location.href = "/donor/dashboard";
                    },
                    error: function(xhr) {
                        swal.fire({
                            title: 'Error',
                            text: 'Login failed: ' + (xhr.responseJSON?.message || 'Something went wrong.'),
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
    
            });
        });
    </script>
</body>

</html>
