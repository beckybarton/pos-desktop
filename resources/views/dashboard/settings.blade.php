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
            <form action="{{route('dashboard.storesetting')}}" method="post">
                @csrf
                <div class="form-group">
                  <label for="name">Company Name:</label>
                  <input type="text" class="form-control" name="company_name" required>
                </div>
                <div class="form-group">
                  <label for="name">Address:</label>
                  <input type="text" class="form-control" name="address" required>
                </div>
                <br>
                <button class="btn btn-success" type="submit" name="settings">Update</button>
            </form>
        </div>

    </div>
</div>

</div>

@include('layouts.footer')