<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statement of Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: left;
            font-weight: bold;
        }

        .company-name {
            font-weight: bold;
            font-size: 14px;
        }

        .section-title {
            font-weight: bold;
            background-color: yellow;
            padding: 5px;
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table tbody td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        .table tfoot td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .totals {
            font-weight: bold;
            text-align: right;
        }

        .signature {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .signature-section {
            width: 45%;
            border-top: 1px solid #000;
            padding-top: 10px;
            text-align: center;
        }

        .prepared-by {
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <div class="header">
        <p class="company-name">{{ $rows[0]['customer_name'] }}</p>
        <p>Plant: BULACAN</p>
        <p>Customer: {{ $rows[0]['customer_name'] }}</p>
        <p>Date: {{ \Carbon\Carbon::now()->format('F d Y,') }}</p>
    </div>
    </div>
    <table class="table">
        <thead>
            <tr class="section-title">
                <th colspan="6" style="text-align: center;">STATEMENT OF ACCOUNT</th>
            </tr>
            <tr>
                <th>Group Customer</th>
                <th>Group Customer Text</th>
                <th>Assignment</th>
                <th>Posting Date</th>
                <th>Due Date</th>
                <th>Bal.AMT(LC)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $amounts = [];
            @endphp
            @foreach ($rows as $row)
                @php
                    $amounts[] = $row['amount'];
                @endphp
                <tr>
                    <td>{{ $group }}</td>
                    <td>{{ $rows[0]['customer_name'] }}</td>
                    <td>{{ $rows[0]['assignment'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($row['posting_date'])->format('m/d/y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($row['due_date'])->format('m/d/y') }}</td>
                    <td>{{ number_format($row['amount'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="totals">Total</td>
                <td class="totals" style="text-align:center;">{{ number_format(array_sum($amounts), 2) }}</td>
            </tr>
        </tfoot>
    </table>
    <div class="signature">
        <div class="signature-section">
            Prepared By:<br><br>
            <div class="prepared-by">JOAN ILLISCUPIDEZ<br>CJ PHILIPPINES, INC.</div>
        </div>
        <div class="signature-section">
            Confirmed By:<br>
            Printed Name over Signature:<br>
            Position / Relation to Dealer:<br>
            Date Confirmed:
        </div>
    </div>
</body>

</html>
