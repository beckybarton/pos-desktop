@include('layouts.header_pdf')
<div class="container mt-5">
    <div class="billing-statement bg-light p-4">
        <div class="header">
            <h2 class="mb-4">C-ONE Sports Center</h2>
            <h3 class="mb-4">Report for {{ Carbon\Carbon::parse($report->start)->format('M d, Y H:i') }} - {{ Carbon\Carbon::parse($report->end)->format('M d, Y H:i')}}</h3>
        </div>
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
        </table>
        <hr>
        <table class="table table-striped table-bordered table-hover small">
            <tr>
                <td colspan="3"><strong>COLLECTIONS</strong></td>
            </tr>
            <tr>
                <td colspan="3"><strong>From Sales</strong></td>
            </tr>
            @foreach ($dailyreport->datecollections as $collection )
                <tr>
                    <td></td>
                    <td>{{ ucwords($collection->method) }}</td>
                    <td class="text-end">{{ number_format($collection->totalpayment,2) }}</td>
                </tr>
            @endforeach 
            @if($dailyreport->collectionsPreviousSalesbyMethod)
                <tr>
                    <td colspan="3"><strong>From Payments of Previous Unpaid Orders</strong></td>
                </tr>
                @foreach ($dailyreport->collectionsPreviousSalesbyMethod as $collection )
                <tr>
                    <td></td>
                    <td>{{ ucwords($collection->method) }}</td>
                    <td class="text-end">{{ number_format($collection->totalpayment,2) }}</td>
                </tr>
                @endforeach
            @endif
            @if($excesspayments->excesspaymentsbymethods)
                <tr>
                    <td colspan="3"><strong>From Excess/Advance Payments</strong></td>
                </tr>
                @foreach ($excesspayments->excesspaymentsbymethods as $excesspaymentsbymethods)
                    <tr>
                        <td></td>
                        <td>{{ ucwords($excesspaymentsbymethods->method) }}</td>
                        <td class="text-end">{{ number_format($excesspaymentsbymethods->total_amount,2) }}</td>
                    </tr>                
                @endforeach
            @endif
            <tr>
                <td colspan="3"><strong>Overall Collections</strong></td>
            </tr>
            @foreach ($dailyreport->allcollections as $allcollection)
                <tr>
                    <td></td>
                    <td>{{ ucwords($allcollection->method) }}</td>
                    <td class="text-end">{{ number_format($allcollection->total_amount,2) }}</td>
                </tr>                
            @endforeach

        </table>
        <hr>
        <table class="table table-bordered table-striped small">
            <tr>
                <td colspan="3"><strong>Cash Breakdown</strong></td>
            </tr> 
            <tr>
                <td>Denomination</td>
                <td class="text-end">Pieces</td>
                <td class="text-end">Total</td>
            </tr>
            @foreach ($cashonhand->cashonhand as $cash)
                <tr>
                    <td>{{ $cash->denomination }}</td>
                    <td class="text-end">{{ $cash->pcs }}</td>
                    <td class="text-end">{{ number_format($cash->amount,2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td class="text-end">Total</td>
                <td class="text-end">{{ number_format($cashonhand->totalcashonhand,2) }}</td>
            </tr>
        </table>
        <hr>
        @if ($dailyreport->collectionsPreviousSalesbyCustomer)
            <table class="table table-striped table-bordered small">
                <tr>
                    <td colspan="3"><strong>List of Payments from Previous Orders</strong></td>
                </tr>
                <tr>
                    <td>Customer</td>
                    <td>Method</td>
                    <td class="text-end">Amount</td>
                </tr>
                @foreach ($dailyreport->collectionsPreviousSalesbyCustomer as $customer)
                    <tr>
                        <td>{{ ucwords($customer->name) }}</td>
                        <td>{{ ucwords($customer->method) }}</td>
                        <td class="text-end">{{ number_format($customer->totalpayment,2) }}</td>
                    </tr>
                @endforeach
            </table>
        <hr>
        @endif
        @if ($excesspayments->excesspaymentsbycustomers)
            <table class="table table-striped table-bordered small">
                <thead>
                    <tr>
                        <td colspan="3"><strong>Excess/Advance Payments from Customers</strong></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Customer</td>
                        <td>Method</td>
                        <td class="text-end">Amount</td>
                    </tr>   
                    @foreach ($excesspayments->excesspaymentsbycustomers as $excess)
                        <tr>
                            <td> {{ ucwords($excess->customer_name) }} </td>
                            <td> {{ ucwords($excess->method) }} </td>
                            <td class="text-end"> {{ number_format($excess->amount,2) }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <hr>
        @endif
        
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
            <tr>
                <td><strong>Total</strong></td>
                <td class="text-end"><strong>{{ number_format($dailyreport->totalunpaid,2) }}</strong></td>
                    
            </tr>
        </table>
        <hr>
        <table class="table table-striped table-bordered small">
            <thead>
                <tr>
                    <td class="thead-dark small" colspan="9"><strong>Sold Items</strong></td>
                </tr>  
            </thead> 
            <thead>
                <tr>
                    <td colspan="4">Items</td>
                    <td colspan="2" class="text-center">Sales</td>
                    <td colspan="2" class="text-center">Cost</td>
                    <td class="text-end">Profit</td>
                </tr>
                <tr>
                    <td class="small">Location</td>
                    <td class="small">Category</td>
                    <td class="small">Item Name</td>
                    <td class="text-end">Quantity</td>
                    <td class="text-end">Price</td>
                    <td class="text-end">Total</td>
                    <td class="text-end">Cost</td>
                    <td class="text-end">Total</td>
                    <td class="text-end">Profit</td>
                </tr>
            </thead>
            @php
                $totalTotalPrice = 0;
                $totalCost = 0;
                $profit = 0;
            @endphp
            @foreach ($solditems->solditems as $solditem)
                <tr>
                    <td class="small">{{ ucwords($solditem->location) }}</td>
                    <td class="small">{{ ucwords($solditem->category_name) }}</td>
                    <td class="small">{{ ucwords($solditem->item_name) }}</td>
                    <td class="text-end small">{{ number_format($solditem->quantity,2) }}</td>
                    <td class="text-end small">{{ number_format($solditem->price,2) }}</td>
                    <td class="text-end small">{{ number_format($solditem->total_price,2) }}</td>
                    <td class="text-end small">{{ number_format($solditem->cost,2) }}</td>
                    <td class="text-end small">{{ number_format(($solditem->cost * $solditem->quantity),2) }}</td>
                    <td class="text-end small">{{ number_format(($solditem->total_price - ($solditem->cost * $solditem->quantity)),2) }}</td>
                    @php
                    $totalTotalPrice += $solditem->total_price;
                    $totalCost += $solditem->cost * $solditem->quantity;
                    $profit += $solditem->total_price - ($solditem->cost * $solditem->quantity);
                @endphp
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-end"><strong>{{ number_format($totalTotalPrice, 2) }}</strong></td>
                <td></td>
                <td class="text-end"><strong>{{ number_format($totalCost, 2) }}</strong></td>
                <td class="text-end"><strong>{{ number_format($profit, 2) }}</strong></td>
            </tr>
        </table>
        <hr>
        <table class="table table-bordered small">
            <thead>
                <tr>
                    <td colspan="11"><strong>Transaction Logs</strong></td>
                </tr>
            </thead>
            <thead>
                <tr>
                    <td colspan="5">Transaction Details</td>
                    <td colspan="3" class="text-center">Sale</td>
                    <td colspan="3" class="text-center">Payment</td>
                </tr>
            </thead>
            <thead>
                <tr>
                    <td>Date/Time</td>
                    <td>Order ID</td>
                    <td>Location</td>
                    <td>Customer Name</td>
                    <td>Item Name</td>
                    <td class="text-end">Price</td>
                    <td class="text-end">Quantity</td>
                    <td class="text-end">Total</td>
                    <td class="text-end">Payment</td>
                    <td class="text-end">Balance</td>
                    <td>Method</td>
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
                        @if ($loop->last && $index + 1 === $totalOrders)
                            <tr class="double-line">
                        @else
                            <tr>
                        @endif
                            <td>{{ Carbon\Carbon::parse($order->created_at)->format('m/d/y h:i A') }}</td>
                            <td>{{ str_pad($orderId, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ ucwords($order->location) }}</td>
                            <td>{{ ucwords($order->customer_name) }}</td>
                            <td>{{ ucwords($order->item_name) }}</td>
                            <td class="text-end">{{ number_format($order->price, 2) }}</td>
                            <td class="text-end">{{ number_format($order->quantity, 2) }}</td>
                            <td class="text-end">{{ number_format($order->total_price, 2) }}</td>
                            @if ($loop->last && $index + 1 === $totalOrders)
                                <td class="text-end text-primary">{{ number_format($order->payment, 2) }}</td>
                            @else
                                <td class="text-end"> </td>
                            @endif

                            @if ($loop->last && $index + 1 === $totalOrders)
                                <td class="text-end text-primary">{{ number_format($order->remaining_due, 2) }}</td>
                            @else
                                <td class="text-end"> </td>
                            @endif
                            @if ($loop->last && $index + 1 === $totalOrders)
                                <td>{{ ucwords($order->method) }}</td>
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

@include('layouts.footer')