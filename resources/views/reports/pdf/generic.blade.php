<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report #{{ $report->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #10233d; }
        table { width: 100%; border-collapse: collapse; margin-top: 24px; }
        th, td { border: 1px solid #d8e1ed; padding: 10px; text-align: left; }
        th { background: #eef3f9; }
    </style>
</head>
<body>
    <h1>{{ ucwords(str_replace('_', ' ', $report->report_type)) }}</h1>
    <p>Generated at {{ optional($report->generated_at)->format('M d, Y h:i A') }}</p>
    <table>
        <thead>
        <tr>
            <th>Label</th>
            <th>Value</th>
        </tr>
        </thead>
        <tbody>
        @foreach (($report->result_data ?? []) as $key => $value)
            <tr>
                <td>{{ is_string($key) ? $key : 'Item '.($loop->iteration) }}</td>
                <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
