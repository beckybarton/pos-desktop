<div class="col-md-3">
    <ul class="list-group">
        <a href="{{ route('pos.index') }}" class="btn btn-success"> Use POS</a>
        <a href="{{ route('item.index') }}" class="list-group-item list-group-item-action"><i class="fas fa-barcode"></i> Items</a>
        <a href="{{ route('location.index') }}" class="list-group-item list-group-item-action"><i class="fas fa-building"></i> Locations</a>
        <a href="{{ route('customer.index') }}" class="list-group-item list-group-item-action"><i class="fas fa-user"></i> Customers</a>
        @if ( auth()->user()->role == "admin" )
            <a href="{{ route('user.index') }}" class="list-group-item list-group-item-action"><i class="fas fa-user"></i> Users</a>
        @endif
        <a href="" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addCategoryModal"><i class="fas fa-clipboard-list"></i> Add Category <i class="fas fa-plus"></i></a>
        <a href="" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addItemModal"><i class="fas fa-clipboard-list"></i> Add Item <i class="fas fa-plus"></i></a>
        <a href=""class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addLocationModal"><i class="fas fa-building"></i> Add Location <i class="fas fa-plus"></i></a>
        <a href=""class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addCustomerModal"><i class="fas fa-user"></i> Add Customer <i class="fas fa-plus"></i></a>
        @if ( auth()->user()->role == "admin" )
            <a href class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="fas fa-user"></i> Add User <i class="fas fa-plus"></i></a>
        @endif
    </ul>
</div>