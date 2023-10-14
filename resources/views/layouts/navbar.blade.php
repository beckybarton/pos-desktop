<!-- Navbar -->
@inject('locationModel', 'App\Models\Location')
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <span class="navbar-brand">Logged in as {{ ucwords(auth()->user()->first_name) }} {{ ucwords(auth()->user()->last_name) }} ({{ auth()->user()->role }}) {{ $locationModel->getLocationNameById(session('selected_location')) }} </span>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                </li>

                <li>
                <button class="nav-link changepassword" id="changepassword" data-target="#changepasswordModal">Change Password</button>
                </li>
            </ul>
        </div>
    </div>
</nav>
<p></p>
@include('items.create')
@include('locations.create')
@include('customers.create')
@include('users.create')
@include('items.create_category')
