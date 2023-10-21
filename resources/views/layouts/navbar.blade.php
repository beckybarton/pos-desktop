<!-- Navbar -->
@inject('locationModel', 'App\Models\Location')
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <span class="navbar-brand">Logged in as {{ ucwords(auth()->user()->first_name) }} {{ ucwords(auth()->user()->last_name) }} ({{ auth()->user()->role }}) {{ $locationModel->getLocationNameById(session('selected_location')) }} </span>
    </div>
</nav>
<p></p>
@include('items.create')
@include('locations.create')
@include('customers.create')
@include('users.create')
@include('items.create_category')
