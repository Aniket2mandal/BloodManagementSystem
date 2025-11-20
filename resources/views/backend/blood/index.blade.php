@extends('backend.layout.app')

@section('content')
    <!-- SweetAlert for session success message -->
    <!-- Store Success Message in a Hidden Input Field -->
    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('blood.index') }}">Bloods</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card-header mt-4">
        <h3 class="card-title">Blood List</h3>
        <div class="card-tools">
            <!-- <div class="input-group input-group-sm" style="width: 150px;"> -->
            <!-- <input type="text" name="table_search" class="form-control float-right" placeholder="Search" /> -->
            @can('create-blood', App\Models\Blood::class)
                <div class="input-group-append">
                    <a href="{{ route('blood.create') }}" type="submit" class="btn btn-success">
                        Add New Blood <i class="fas fa-plus"></i>
                    </a>
                </div>
            @endcan
            <!-- </div> -->
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped mt-4">
            <thead class="table table-dark">
                <tr class="table">
                    <!-- <th style="width: 10px"></th> -->
                    <th>S.N.</th>
                    <th>Blood Group</th>
                    <th>Blood Bank</th>
                    <th>Quantity</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($blood as $bloods)
                    <tr class="align-middle" id="post-{{ $bloods->id }}">
                        <td>{{ $i++ }}</td>
                        <td>{{ $bloods->name }}</td>
                        <td>
                            @foreach ($bloods->bloodBanks as $bank)
                                {{ $bank->name }} <br>
                            @endforeach
                        </td>
                        <td>
                            @if ($bloods->bloodBanks->isEmpty() && $donatedByGroup->isNotEmpty())
                                @foreach ($donatedByGroup as $donatedGroup => $donatedquantity)
                                    @if ($bloods->name == $donatedGroup)
                                        {{ $donatedquantity }} pint <br>
                                    @endif
                                @endforeach
                            @elseif (!$donatedByGroup->has($bloods->name) && $bloods->bloodBanks->isNotEmpty())
                                @foreach ($bloods->bloodBanks as $quantity)
                                    {{ $quantity->pivot->quantity ?? 0 }} pint <br>
                                @endforeach
                            @else
                                {{-- @foreach ($bloods->bloodBanks as $quantity)
                                    @foreach ($donatedByGroup as $donatedGroup => $donatedquantity)
                                        @if ($bloods->name == $donatedGroup)
                                            {{ $donatedquantity + $quantity->pivot->quantity }} pint <br>
                                        @endif
                                    @endforeach
                                @endforeach --}}

                                @foreach ($bloods->bloodBanks as $bank)
                                @foreach ($donatedByGroup as $donatedGroup => $donatedBanks)
                                    @if ($bloods->name == $donatedGroup)
                                        @foreach ($donatedBanks as $bankName => $quantity)
                                            @if ($bank->name == $bankName)
                                               {{ $quantity +$bank->pivot->quantity}} pint <br>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endforeach
                            @endif
                        </td>

                        <td>
                            {!! Str::words($bloods->description, 20) !!} <br>
                        </td>

                        <td>

                            @can('add-quantity', $bloods)
                                <button class="btn btn-success btn-sm addbloodbank" data-id="{{ $bloods->id }}"
                                    data-toggle="modal" data-target="#locationModal">
                                    <i class="fas fa-plus"></i> <b>Add Quantity</b></button>
                            @endcan

                            @can('edit-blood', $bloods)
                                <a href="{{ route('blood.edit', $bloods->id) }}" class="btn btn-primary btn-sm me-2 d-inline">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>



        <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content border-0 rounded">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="locationModalLabel">Donation Detail</h5>
                        <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form id="locationForm">
                        @csrf
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="Quantity" class="form-label">Quantity</label>
                                <input type="text" class="form-control" name="quantity" id="quantity"
                                    placeholder="10 units">
                            </div>

                            <div class="mb-3">
                                <label for="state" class="form-label">Blood Bank</label>
                                <select class="form-control" name="bloodbank" id="bloodbank">
                                    <option value="" disabled selected>Select Blood Bank</option>
                                    {{-- <pre>{{ var_dump($bloodbank) }}</pre> --}}
                                    @foreach ($userBloodBanks as $bank)
                                        <option value="{{ $bank->id }}"
                                            {{ old('bloodbank', isset($bank) ? 'selected' : '') }}>{{ $bank->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-dismiss="modal">cancel</button>
                            <button type="submit" class="btn btn-dark">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Pagination -->
        <div class="card-footer clearfix">
            {{ $blood->links('pagination::bootstrap-4') }}
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.Status').change(function() {
                var bloodId = $(this).data('id');
                console.log(bloodId);
                var Status = $(this).prop('checked') ? '1' : '0';
                console.log(Status);
                $.ajax({
                    method: 'POST',
                    url: '/blood/status/' + bloodId,
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'Status': Status
                    },
                    success: function(response) {

                        // SweetAlert2 success popup
                        Swal.fire({
                            title: 'Success!',
                            text: 'The user status has been updated.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                            // Remove the post element from the DOM (you can select the post by its ID or class)
                            // $('#post-' + postId).remove(); // Assuming each post has an id like "post-1", "post-2", etc.
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        Swal.fire({
                            title: 'Error!',
                            text: 'The is error updating status !',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            $('.delete-btn').click(function() {
                var bloodId = $(this).data('id');
                console.log(bloodId);
                swal.fire({
                    title: "Are You Sure?",
                    text: "Do you want to delete the item ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log("yess it is");
                        $.ajax({
                            method: 'GET',
                            url: '/blood/delete/' + bloodId,

                            success: function(response) {

                                // SweetAlert2 success popup
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'The user deleted sucessfully.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    location.reload();
                                    // Remove the post element from the DOM (you can select the post by its ID or class)
                                    $('#post-' + bloodId)
                                        .remove(); // Assuming each post has an id like "post-1", "post-2", etc.
                                });
                            },
                            error: function(xhr, status, error) {
                                // Handle any errors
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'An error occurred while deleting the user.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }

                });
            });


        });
    </script>


    <script>
        $(document).ready(function() {
            var id;

            // Handle the click event for the "Add Blood Bank" button
            $(".addbloodbank").on('click', function(event) {
                id = $(this).data('id');
                console.log(id);
            });

            $("#locationForm").on('submit', function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                formData += '&blood_id=' + id;
                console.log(formData);

                $.ajax({
                    url: "{{ route('blood.addbloodquantity') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Blood bank aand quantity dded successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error adding blood bank:", error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while adding the blood bank.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        })
    </script>
@endsection
