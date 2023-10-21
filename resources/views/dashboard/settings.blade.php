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
                @if($setting)
                    <div class="form-group">
                        <label for="name">Company Name:</label>
                        <input type="text" class="form-control" name="company_name" value="{{ $setting->company_name }}" required>
                    </div>
                    <div class="form-group">
                    <label for="name">Address:</label>
                    <input type="text" class="form-control" name="address" value="{{ $setting->address }}" required>
                    </div>
                    <div class="form-group">
                        <label for="timezone">Select Timezone:</label>
                        <select class="form-control" id="timezone" name="timezone">
                            @foreach(timezone_identifiers_list() as $timezone)
                                <option value="{{ $timezone }}">{{ $timezone }}</option>
                            @endforeach
                        </select>
                    </div>
                
                @else
                    <div class="form-group">
                        <label for="name">Company Name:</label>
                        <input type="text" class="form-control" name="company_name" required>
                      </div>
                      <div class="form-group">
                        <label for="name">Address:</label>
                        <input type="text" class="form-control" name="address" required>
                      </div>
                    <div class="form-group">
                        <label for="timezone">Select Timezone:</label>
                        <select class="form-control" id="timezone" name="timezone">
                            @foreach(timezone_identifiers_list() as $timezone)
                                <option value="{{ $timezone }}">{{ $timezone }}</option>
                            @endforeach
                        </select>
                    </div>
                
                @endif
                
                <br>
                <button class="btn btn-success" type="submit" name="settings">Update</button>
            </form>
        </div>

    </div>
</div>

</div>

@include('layouts.footer')