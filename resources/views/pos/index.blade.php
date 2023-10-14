@include('layouts.header')
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <span class="navbar-brand"> {{$setting->company_name}} </span>
        </div>
    </nav>
    <div class="row" style="margin-top: 5px;">
        <div class="col-md-8" style="max-height:75vh; overflow-y: scroll;">
            <table class="table" id="selectedItemsTable">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Description</th>
                    <th scope="col">UOM</th>
                    <th scope="col" style="width: 150px;" class="text-end">Unit Price</th>
                    <th scope="col" style="width: 100px;" class="text-end">Qty</th>
                    <th scope="col" class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="col-md-4 bg-light p-3">
            <div class="row mb-3">
                <label for="due" class="col-sm-5 col-form-label text-danger"><strong>Amount Due:</strong></label>
                <div class="col-sm-7">
                    <!-- <span id="dueAmount" class="text-danger"><strong>0.00</strong></span> -->
                    <input type="text" readonly class="form-control-plaintext text-danger" id="dueAmount" name="dueAmount">
                </div>
            </div>

            <div class="row mb-3">
                <label for="change" class="col-sm-5 col-form-label text-success"><strong>Change:</strong></label>
                <div class="col-sm-7">
                    <!-- <span id="change" class="text-success"><strong>0.00</strong></span> -->
                    <input type="text" readonly class="form-control-plaintext text-success" id="change" name="change">
                </div>
            </div>

            <div class="row mb-3">
                <label for="received" class="col-sm-5 col-form-label font-weight-bold">Amount Received:</label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" id="received" name="received">
                </div>
            </div>

            <div class="row mb-3">
                <label for="method" class="col-sm-5 col-form-label font-weight-bold">Payment Method:</label>
                <div class="col-sm-7">
                    <!-- <input type="text" class="form-control" id="method" name="method"> -->
                    <select class="form-control" id="method" name="method" required>
                        <option value="0">Mark Unpaid</option>
                        <option value="1">Cash</option>
                        <option value="2">Gcash</option>
                        <option value="3">Card</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label for="customer" class="col-sm-5 col-form-label font-weight-bold">Customer:</label>
                <div class="col-sm-7">
                    <input type="text" hidden readonly class="form-control-plaintext" id="customer" name="customer">
                    <input type="text" readonly class="form-control-plaintext" id="customername" name="customername">
                </div>
            </div>

            <div class="row mb-3">
                <label for="cashiertext" class="col-sm-5 col-form-label font-weight-bold">Cashier:</label>
                <div class="col-sm-7">
                    <input type="text" readonly value="{{ ucwords(auth()->user()->first_name) }}" class="form-control-plaintext" id="cashiertext" name="cashiertext">
                </div>
            </div>

            <div class="row mb-3">
                @inject('locationModel', 'App\Models\Location')
                <label for="locationtext" class="col-sm-5 col-form-label font-weight-bold">Location:</label>
                <div class="col-sm-7">
                    <input type="text" readonly value="{{ $locationModel->getLocationNameById(session('selected_location')) }}" class="form-control-plaintext" id="locationtext" name="locationtext">
                </div>
            </div>
        </div>



    </div>
@include('layouts.footer')
@include('pos.search')
@include('pos.customer')