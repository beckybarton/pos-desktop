<div class="col-md-3">
    <ul class="list-group">
        <div class="mb-3">
            <a href="{{ route('pos.index') }}" class="btn btn-success mb-2 d-block"> Use POS</a>
            <button class="list-group-item list-group-item-action"" type="button" data-bs-toggle="collapse" data-bs-target="#menuCollapse" aria-expanded="false">
                Menu
            </button>
            <div class="collapse mt-2" id="menuCollapse">
                <a href="{{ route('item.categories') }}" class="list-group-item list-group-item-action"><i class="fas fa-tag"></i> Categories</a>
                <a href="{{ route('item.index') }}" class="list-group-item list-group-item-action"><i class="fas fa-barcode"></i> Items</a>
                <a href="{{ route('location.index') }}" class="list-group-item list-group-item-action"><i class="fas fa-building"></i> Locations</a>
                <a href="{{ route('customer.index') }}" class="list-group-item list-group-item-action"><i class="fas fa-user"></i> Customers</a>
                @if (auth()->user()->role == "admin")
                    <a href="{{ route('user.index') }}" class="list-group-item list-group-item-action"><i class="fas fa-user"></i> Users</a>
                    <a href="{{ route('report.index') }}" class="list-group-item list-group-item-action"><i class="fas fa-file"></i> Reports</a>
                    <a href="{{ route('report.previous') }}" class="list-group-item list-group-item-action"><i class="fas fa-file"></i> Previous Reports</a>
                @endif
            </div>
        </div>

        <div class="mb-3">
            <button class="list-group-item list-group-item-action" type="button" data-bs-toggle="collapse" data-bs-target="#addActionsCollapse" aria-expanded="false">
                Add Actions
            </button>
            <div class="collapse mt-2" id="addActionsCollapse">
                <a href="" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fas fa-clipboard-list"></i> Add Category <i class="fas fa-plus"></i>
                </a>
                <a href="" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addItemModal">
                    <i class="fas fa-clipboard-list"></i> Add Item <i class="fas fa-plus"></i>
                </a>
                <a href="" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addLocationModal">
                    <i class="fas fa-building"></i> Add Location <i class="fas fa-plus"></i>
                </a>
                <a href="" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                    <i class="fas fa-user"></i> Add Customer <i class="fas fa-plus"></i>
                </a>
                @if (auth()->user()->role == "admin")
                    <a href="" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-user"></i> Add User <i class="fas fa-plus"></i>
                    </a>
                @endif
            </div>
        </div>

        <a href="#settingsCollapse" class="list-group-item list-group-item-action" data-bs-toggle="collapse" aria-expanded="false">
            <i class="fas fa-cogs"></i> Settings
        </a>
        
        <div class="collapse" id="settingsCollapse">
            <div class="mb-3">
                @if (auth()->user()->role == "admin")
                    <a href="{{ route('setting.company') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-cogs"></i> Company Information
                    </a>
                    <a href="{{ route('setting.denominations') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-money-bill"></i> Denominations
                    </a>
                @endif
                <button class="list-group-item list-group-item-action btn btn-primary changepassword" id="changepassword" data-bs-target="#changepasswordModal">
                    <i class="fas fa-key"></i> Change Password
                </button>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-danger list-group-item list-group-item-action" type="submit">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
        
        

        {{-- <a href="" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addCategoryModal"><i class="fas fa-clipboard-list"></i> Add Category <i class="fas fa-plus"></i></a>
        <a href="" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addItemModal"><i class="fas fa-clipboard-list"></i> Add Item <i class="fas fa-plus"></i></a>
        <a href=""class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addLocationModal"><i class="fas fa-building"></i> Add Location <i class="fas fa-plus"></i></a>
        <a href=""class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addCustomerModal"><i class="fas fa-user"></i> Add Customer <i class="fas fa-plus"></i></a> --}}
        {{-- @if ( auth()->user()->role == "admin" )
            <a href="{{ route('dashboard.setting') }}" class="list-group-item list-group-item-action"><i class="fas fa-cogs"></i> Company Information</a>
        @endif
        <button class="list-group-item list-group-item-action btn btn-primary changepassword" id="changepassword" data-target="#changepasswordModal">Change Password</button>
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-danger list-group-item list-group-item-action" type="submit">Logout</button>
        </form> --}}
        
    </ul>
</div>