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
                {{-- @foreach ($unpaidtransactions as $unpaidtransaction)
                    <tr>
                        <td class="small text-left">{{ str_pad($unpaidtransaction->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="small text-left">{{ Carbon\Carbon::parse($unpaidtransaction->created_at)->format('M d, Y') }}</td>
                        <td class="small text-left">{{ ucwords($unpaidtransaction->vehicle_type->name) }}</td>
                        <td class="small text-left">{{ strtoupper($unpaidtransaction->plate_number) }}</td>
                        <td class="text-end small">{{ number_format($unpaidtransaction->amount,2) }}</td>
                    </tr>   
                @endforeach --}}
                {{-- <tr>
                    <td class="small text-left" colspan="4"><strong>Total Payable: </strong></td>
                    <td class="text-end small"><strong>{{number_format($totalPayable,2)}}</strong></td>
                </tr> --}}
            </table>
            <hr>
            <p class="small">Prepared by: {{ ucwords($report->user->first_name) }} {{ ucwords($report->user->last_name) }}</p>
        </div>
    </div>
</div>

@include('layouts.footer')