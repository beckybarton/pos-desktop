@include('layouts.header')
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <span class="navbar-brand"> {{$setting->company_name}} </span>
        </div>
    </nav>
    <div class="row">
        <div class="col-md-8">
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
            summary here
        </div> 
        <div class="col-md-3">
            summary here
        </div>  
    </div>
@include('layouts.footer')
@include('pos.search')