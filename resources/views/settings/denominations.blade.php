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
            <form action="{{route('setting.storesetting')}}" method="post">
                @csrf
                
                
                <br>
                <button class="btn btn-success" type="submit" name="settings">Update</button>
            </form>
        </div>

    </div>
</div>

</div>

@include('layouts.footer')