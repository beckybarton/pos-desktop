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
            <form action="{{ route('setting.storedenomination') }}" method="post" class="row g-3">
                @csrf
                <div id="denominationsContainer">
                    <label for="amounts" class="form-label">Denominations</label>
                    @foreach($denominations as $denomination)
                        <input type="number" required class="form-control" name="amounts[]" id="amounts" value="{{ $denomination->amount }}" required>
                        <br>
                    @endforeach
                    <input type="number" required class="form-control" name="amounts[]" id="amounts" required>
                </div>
                
                <hr>
                <div class="col-md-6">
                    <button type="button" id="addDenominationdBtn" class="btn btn-primary mr-2">Add Denomination</button>
                    <button class="btn btn-success" type="submit" name="settings">Update</button>
                </div>
            </form>
        </div>

    </div>
</div>

</div>

@include('layouts.footer')