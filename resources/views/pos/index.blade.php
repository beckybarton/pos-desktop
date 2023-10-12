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
        <div class="col-md-1">
            
        </div> 
        <div class="col-md-3 bg-light d-flex flex-column align-items-center p-3">
            <p class="text-center font-weight-bold mb-4"><strong>SUMMARY</strong></p>
            <div class="mb-3">
                <label for="due" class="form-label text-danger"><strong>Amount Due:</strong></label>
                <span id="dueAmount" class="text-danger"><strong>0.00</strong></span>
            </div>
            <div class="mb-3">
                <label for="received" class="form-label">Amount Received:</label>
                <input type="number" class="form-control" id="received" name="received">
            </div>
            <div class="mb-3">
                <label for="cashier" class="form-label">Cashier:</label>
                <input type="text" class="form-control" id="cashier" name="cashier">
            </div>
            <div class="mb-3">
                <label for="method" class="form-label">Payment Method:</label>
                <input type="text" class="form-control" id="method" name="method">
            </div>
        </div>

    </div>
@include('layouts.footer')
@include('pos.search')