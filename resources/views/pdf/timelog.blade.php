<html lang="en">

<head>
    <title>Invoice</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
        }

        @page {
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .page-break {
            page-break-after: always;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
            padding: 0.75rem;
            vertical-align: top;
            text-align: left;
        }

        .table-bordered th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .table-bordered td {
            background-color: #ffffff;
        }

        .table td,
        .table th {
            padding: .75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .text-center {
            text-align: center !important;
        }
    </style>
</head>

<body>

    <div class="">
        <div class="">
            <div class="">
                <h2 class="text-center">Export Time Logs</h2>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="">Project Name</th>
                    <th class="">Start Time</th>
                    <th class="">End Time</th>
                    <th class="">Hours</th>
                    <th class="">Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($time_logs as $time_log)
                    <tr>
                        <td class="">
                            {{ $time_log->project->title }}
                        </td>
                        <td class="">
                            {{ \Carbon\Carbon::parse($time_log->start_time)->format('d/m/Y H:i') }}
                        </td>
                        <td class="">
                            {{ \Carbon\Carbon::parse($time_log->end_time)->format('d/m/Y H:i') }}
                        </td>
                        <td class="">
                            {{ $time_log->hours }}
                        </td>
                        <td class="">
                            {{ $time_log->description }}
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

</body>

</html>
