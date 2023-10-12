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
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ number_format($item->selling_price,2) }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-item" id="edit-item" data-target="#editItemModal" data-item="{{ $item }}">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $items->links('pagination::bootstrap-4') }}
            </div>
            </div>
        </div>

    </div>
</div>

</div>

@include('layouts.footer')
@include('items.edit')