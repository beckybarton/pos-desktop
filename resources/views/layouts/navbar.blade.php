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
                <li>
                    <button class="btn btn-primary changepassword" id="changepassword" data-target="#changepasswordModal">Change Password</button>
                </li>
                <li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-danger" type="submit">Logout</button>
                    </form>
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
