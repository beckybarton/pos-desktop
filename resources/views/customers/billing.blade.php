<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>C-ONE Sports Center</title>
    <style>
         body {
            font-family: Arial, sans-serif;
        }

        .billing-statement {
            max-width: 800px;
            margin: 0 auto;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        .header {
            text-align: center;
            padding: 0;
        }

        .customer-info {
            margin-bottom: 30px;
        }
        table.table-striped tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        table.table-bordered,
        table.table-bordered th,
        table.table-bordered td {
            border: 1px solid #ddd; /* Border color */
        }

        table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 0.25rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }

        .text-end {
            text-align: right;
        }

        .small {
            font-size: 0.875rem; /* 14px */
        }

        .text-left{
            text-align: left;
        }




    </style>
</head>

<body>
    @php
        $companydetails = (new \App\Models\Setting)->companydetails();
    @endphp
<div class="container mt-5">
    <div class="billing-statement bg-light p-4">
        <div class="header" style="padding: 10px !important;">
            <h2>C-ONE Sports Center</h2>
            <h3>Billing Statement</h3>
            <p>{{ ucwords($companydetails[0]->address) }}</p>
        </div>

        <div class="customer-info">
            <p><strong>Customer Name:</strong> {{ ucwords($customerorders['customer_name']) }}</p>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="small text-left">ID</th>
                        <th class="small text-left">Date</th>
                        <th class="small text-left">Location</th>
                        <th class="small text-left">Item</th>
                        <th class="small text-end">Qty</th>
                        <th class="small text-end">Unit Price</th>
                        <th class="text-end small">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customerorders['orders'] as $customerorder)
                        <tr>
                            <td class="small text-left">{{ str_pad($customerorder->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="small text-left">{{ Carbon\Carbon::parse($customerorder->created_at)->format('M d, Y') }}</td>
                            <td class="small text-left">{{ ucwords($customerorder->location_name) }}</td>
                            <td class="small text-left">{{ ucwords($customerorder->item_name) }}</td>
                            <td class="text-end small">{{ number_format($customerorder->price,2) }}</td>
                            <td class="text-end small">{{ number_format($customerorder->quantity,2) }}</td>
                            <td class="text-end small">{{ number_format(($customerorder->quantity * $customerorder->price),2) }}</td>
                        </tr>   
                    @endforeach
                    <tr>
                        <td class="small text-left" colspan="6"><strong>Total Payable: </strong></td>
                        <td class="text-end small"><strong>{{number_format($customerorders['total_payable'],2)}}</strong></td>
                    </tr>
                    <tr>
                        <td class="small text-left" colspan="6"><strong>Partial Payments: </strong></td>
                        <td class="text-end small"><strong>{{number_format($customerorders['total_payment'],2)}}</strong></td>
                    </tr>
                    <tr>
                        <td class="small text-left" colspan="6"><strong>Remaining Due: </strong></td>
                        <td class="text-end small"><strong>{{number_format($customerorders['total_remaining_due'],2)}}</strong></td>
                    </tr>
                </tbody>
            </table>
            <hr>
            <p class="small">Please make checks payable to: {{ strtoupper($companydetails[0]->company_name) }}</p>
        </div>
    </div>
</div>

<!-- Bootstrap JS (optional) -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script> -->
</body>

</html>
