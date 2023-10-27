@include('layouts.header_pdf')
<div class="container mt-5">
    <div class="billing-statement bg-light p-4">
        <div class="header">
            <h2 class="mb-4">C-ONE Sports Center</h2>
            <h3 class="mb-4">Daily Report for {{ Carbon\Carbon::parse($report->start)->format('M d, Y') }} - {{ Carbon\Carbon::parse($report->end)->format('M d, Y')}}</h3>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <tr>
                    <td colspan="2"><strong>Total Sales</strong></td>
                    <td class="text-end">{{ number_format($dailyreport->sumOrdersAmount,2) }}
                </tr>
                <tr>
                    <td colspan="3"><strong>Categorized Sales</strong></td>
                </tr>
                @foreach ($dailyreport->categorizedSales as $categorizedSale )
                    <tr>
                        <td></td>
                        <td>{{ ucwords($categorizedSale->category_name) }}
                        <td class="text-end">{{ number_format($categorizedSale->amount,2) }}
                    </tr>
                @endforeach

                <tr>
                    <td colspan="3"><strong>Collections from Today's Sales</strong></td>
                </tr>
                @foreach ($dailyreport->collectionsDateSalesbyMethod as $collection )
                    <tr>
                        <td></td>
                        <td>{{ ucwords($collection->method) }}</td>
                        <td class="text-end">{{ number_format($collection->totalpayment,2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td>Unpaid</td>
                    <td class="text-end">{{ number_format($dailyreport->totalunpaid,2) }}</td>
                </tr>

                <tr>
                    <td colspan="3"><strong>Collections from Previous Unpaid Customers</strong></td>
                </tr>
                @foreach ($dailyreport->collectionsPreviousSalesbyMethod as $collection )
                <tr>
                    <td></td>
                    <td>{{ ucwords($collection->method) }}</td>
                    <td class="text-end">{{ number_format($collection->totalpayment,2) }}</td>
                </tr>
                @endforeach

                <tr>
                    <td colspan="3"><strong>Total Collections</strong></td>
                </tr>
                @foreach ($dailyreport->allcollections as $collection )
                <tr>
                    <td></td>
                    <td>{{ ucwords($collection->method) }}</td>
                    <td class="text-end">{{ number_format($collection->totalpayment,2) }}</td>
                </tr>
                @endforeach

                <tr>
                    <td></td>
                    <td><strong>Total Collections</strong></td>
                    <td class="text-end">{{ number_format($dailyreport->totalcollections,2) }}</td>
                </tr>
            </table>
            <hr>
            <table class="table table-striped table-bordered">
                <tr>
                    <td colspan="2"><strong>List of Unpaid Customers</strong></td>
                </tr>
                @foreach ($dailyreport->listunpaidcustomers as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td class="text-end">{{ number_format($customer->amount,2) }}</td>
                    </tr>
                @endforeach
            </table>
            <hr>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <td class="thead-dark" colspan="5"><strong>Sold Items</strong></td>
                    </tr>  
                </thead> 
                <thead>
                    <tr>
                        <td>Category</td>
                        <td>Item Name</td>
                        <td class="text-end">Price</td>
                        <td class="text-end">Quantity</td>
                        <td class="text-end">Total</td>
                    </tr>
                </thead>
                @foreach ($solditems->solditems as $solditem)
                    <tr>
                        <td>{{ $solditem->category_name }}</td>
                        <td>{{ $solditem->item_name }}</td>
                        <td class="text-end">{{ number_format($solditem->price,2) }}</td>
                        <td class="text-end">{{ number_format($solditem->quantity,2) }}</td>
                        <td class="text-end">{{ number_format($solditem->total_price,2) }}</td>
                        {{-- <td class="text-end">{{ number_format($solditem->amount,2) }}</td> --}}
                    </tr>
                @endforeach
            </table>
            <hr>
            <p class="small">Prepared by: {{ ucwords($report->user->first_name) }} {{ ucwords($report->user->last_name) }}</p>
        </div>
    </div>
</div>

@include('layouts.footer')