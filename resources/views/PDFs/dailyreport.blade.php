@include('layouts.header_pdf')
<div class="container mt-5">
    <div class="billing-statement bg-light p-4">
        <div class="header">
            <h2 class="mb-4">C-ONE Sports Center</h2>
            <h3 class="mb-4">Daily Report for {{ Carbon\Carbon::parse($report->start)->format('M d, Y') }} - {{ Carbon\Carbon::parse($report->end)->format('M d, Y')}}</h3>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover small">
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
            <table class="table table-striped table-bordered small">
                <tr>
                    <td colspan="2"><strong>List of Payments from Previous Orders</strong></td>
                </tr>
                @foreach ($dailyreport->collectionsPreviousSalesbyCustomer as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td class="text-end">{{ number_format($customer->totalpayment,2) }}</td>
                    </tr>
                @endforeach
            </table>
            <hr>
            <table class="table table-striped table-bordered small">
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
            <table class="table table-striped table-bordered small">
                <thead>
                    <tr>
                        <td class="thead-dark small" colspan="5"><strong>Sold Items</strong></td>
                    </tr>  
                </thead> 
                <thead>
                    <tr>
                        <td class="small">Category</td>
                        <td class="small">Item Name</td>
                        <td class="text-end">Price</td>
                        <td class="text-end">Quantity</td>
                        <td class="text-end">Total</td>
                    </tr>
                </thead>
                @foreach ($solditems->solditems as $solditem)
                    <tr>
                        <td class="small">{{ $solditem->category_name }}</td>
                        <td class="small">{{ $solditem->item_name }}</td>
                        <td class="text-end small">{{ number_format($solditem->price,2) }}</td>
                        <td class="text-end small">{{ number_format($solditem->quantity,2) }}</td>
                        <td class="text-end small">{{ number_format($solditem->total_price,2) }}</td>
                    </tr>
                @endforeach
            </table>
            <hr>
            <table class="table table-bordered small">
                <thead>
                    <tr>
                        <td colspan="6"><strong>Transaction Logs</strong></td>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <td>Date/Time</td>
                        <td>Order ID</td>
                        <td>Customer Name</td>
                        <td>Item Name</td>
                        <td class="text-end">Price</td>
                        <td class="text-end">Quantity</td>
                        <td class="text-end">Total</td>
                        <td class="text-end">Payment</td>
                        <td class="text-end">Balance</td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $groupedOrders = collect($orderitemspercustomer->orderitemspercustomer)->groupBy('order_id');
                    @endphp

                    @foreach ($groupedOrders as $orderId => $orders)
                        @php
                            $totalOrders = count($orders);
                        @endphp

                        @foreach ($orders as $index => $order)
                            <tr>
                                <td>{{ Carbon\Carbon::parse($order->created_at)->format('m/d/y h:i A') }}</td>
                                <td>{{ str_pad($orderId, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ ucwords($order->customer_name) }}</td>
                                <td>{{ ucwords($order->item_name) }}</td>
                                <td class="text-end">{{ number_format($order->price, 2) }}</td>
                                <td class="text-end">{{ number_format($order->quantity, 2) }}</td>
                                <td class="text-end">{{ number_format($order->total_price, 2) }}</td>
                                @if ($loop->last && $index + 1 === $totalOrders)
                                    <td class="text-end text-primary"><strong>{{ number_format($order->payment, 2) }}</strong></td>
                                @else
                                    <td class="text-end"> </td>
                                @endif
                                @if ($loop->last && $index + 1 === $totalOrders)
                                    <td class="text-end text-primary"><strong>{{ number_format($order->remaining_due, 2) }}</strong></td>
                                @else
                                    <td class="text-end"> </td>
                                @endif
                                
                            </tr>
                        @endforeach
                    @endforeach

                </tbody>
            </table>
            <hr>
            <p class="small">Prepared by: {{ ucwords($report->user->first_name) }} {{ ucwords($report->user->last_name) }}</p>
        </div>
    </div>
</div>

@include('layouts.footer')