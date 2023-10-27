<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .billing-statement {
            max-width: 800px;
            margin: 0 auto;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        .header {
            text-align: center;
        }

        .customer-info {
            margin-bottom: 10px;
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
            padding: 0.10rem;
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
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
