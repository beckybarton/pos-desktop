@include('layouts.header')
@include('layouts.navbar')
<div class="row">
    @include('layouts.sidebar')
    <div class="col-md-9">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
            {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible">
            {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <input type="text" class="form-control" id="searchinputitem" placeholder="Search by Name">
            <p></p>
            <table class="table table-striped table-bordered table-hover" id="itemstable">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ ucwords($user->first_name) }} {{ ucwords($user->last_name) }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ ucwords($user->role) }}</td>
                            <td>Action</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
            </div>
        </div>

    </div>
</div>

</div>

@include('layouts.footer')