
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
                        <th class="text-end">Remaining due</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <td class="customer-id">{{ $customer->id }}</td>
                            <td>{{ $customer->name }}</td>
                            <td class="text-end">{{ number_format($customer->total_remaining_due,2) }}</td>
                            <td><button class="btn btn-primary view-customer">View</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $customers->links('pagination::bootstrap-4') }}
            </div>
            </div>
        </div>

    </div>
</div>

</div>

@include('layouts.footer')
@include('pos.receivablescustomer')