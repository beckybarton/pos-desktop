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
            <table class="table table-striped table-bordered table-hover" id="itemstable">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locations as $location)
                        <tr>
                            <td>{{ $location->id }}</td>
                            <td>{{ $location->name }}</td>
                            <td>Action</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            </div>
        </div>

    </div>
</div>

</div>

@include('layouts.footer')