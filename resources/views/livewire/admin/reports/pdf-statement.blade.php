<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Statement Report</title>
    <style>
        /* PDF Styles */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #1e40af;
            margin-bottom: 5px;
            font-size: 24px;
        }

        .header p {
            color: #6b7280;
            margin: 5px 0;
        }

        .summary-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
        }

        .card {
            flex: 1;
            min-width: 200px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            background: #f9fafb;
        }

        .card-title {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .card-value {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
        }

        .card-income { border-left: 4px solid #10b981; }
        .card-expense { border-left: 4px solid #ef4444; }
        .card-net { border-left: 4px solid #3b82f6; }
        .card-count { border-left: 4px solid #8b5cf6; }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            margin: 25px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #f3f4f6;
            text-align: left;
            padding: 10px;
            border: 1px solid #e5e7eb;
            font-weight: 600;
            color: #374151;
        }

        td {
            padding: 10px;
            border: 1px solid #e5e7eb;
        }

        .positive { color: #10b981; }
        .negative { color: #ef4444; }

        .category-table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .chart-container {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #f9fafb;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 11px;
        }

        .page-break {
            page-break-before: always;
        }

        /* Color helpers */
        .text-green { color: #10b981; }
        .text-red { color: #ef4444; }
        .text-blue { color: #3b82f6; }

        .bg-green { background-color: #10b98120; }
        .bg-red { background-color: #ef444420; }
        .bg-blue { background-color: #3b82f620; }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
        }

        .badge-income { background-color: #10b98120; color: #047857; }
        .badge-expense { background-color: #ef444420; color: #b91c1c; }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <h1>Financial Statement Report</h1>
        <p>Generated on {{ now()->format('F d, Y \\a\\t h:i A') }}</p>
        <p>Period: {{ $startDate->format('F d, Y') }} - {{ $endDate->format('F d, Y') }}</p>
        <p>Report Type: {{ ucfirst($reportType) }}</p>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="card card-income">
            <div class="card-title">Total Income</div>
            <div class="card-value">${{ number_format((float)($data['totalIncome'] ?? 0), 2) }}</div>
        </div>

        <div class="card card-expense">
            <div class="card-title">Total Expenses</div>
            <div class="card-value">${{ number_format((float)($data['totalExpenses'] ?? 0), 2) }}</div>
        </div>

        <div class="card card-net">
            <div class="card-title">Net Income</div>
            <div class="card-value {{ ($data['netIncome'] ?? 0) >= 0 ? 'text-green' : 'text-red' }}">
                ${{ number_format((float)($data['netIncome'] ?? 0), 2) }}
            </div>
        </div>

        <div class="card card-count">
            <div class="card-title">Total Transactions</div>
            <div class="card-value">{{ number_format((int)($data['transactionCount'] ?? 0)) }}</div>
            <div style="font-size: 11px; color: #6b7280; margin-top: 5px;">
                Average: ${{ number_format((float)($data['averageTransaction'] ?? 0), 2) }}
            </div>
        </div>
    </div>

    <!-- Income Breakdown -->
    <div class="section-title">Income Breakdown by Category</div>
    @if($data['incomeByCategory']->count() > 0)
        <table class="category-table">
            <thead>
                <tr>
                    <th width="50%">Category</th>
                    <th width="20%">Transactions</th>
                    <th width="30%">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['incomeByCategory'] as $category)
                    <tr>
                        <td>{{ $category->category }}</td>
                        <td>{{ $category->count }}</td>
                        <td class="text-green">${{ number_format((float)($category->total ?? 0), 2) }}</td>
                    </tr>
                @endforeach
                <tr style="font-weight: bold; background-color: #f0fdf4;">
                    <td colspan="2">Total Income</td>
                    <td class="text-green">${{ number_format((float)($data['totalIncome'] ?? 0), 2) }}</td>
                </tr>
            </tbody>
        </table>
    @else
        <p style="color: #6b7280; font-style: italic; padding: 15px; background: #f9fafb; border-radius: 6px;">
            No income transactions found in the selected period.
        </p>
    @endif

    <!-- Expense Breakdown -->
    <div class="section-title">Expense Breakdown by Category</div>
    @if($data['expensesByCategory']->count() > 0)
        <table class="category-table">
            <thead>
                <tr>
                    <th width="50%">Category</th>
                    <th width="20%">Transactions</th>
                    <th width="30%">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['expensesByCategory'] as $category)
                    <tr>
                        <td>{{ $category->category }}</td>
                        <td>{{ $category->count }}</td>
                        <td class="text-red">${{ number_format((float)($category->total ?? 0), 2) }}</td>
                    </tr>
                @endforeach
                <tr style="font-weight: bold; background-color: #fef2f2;">
                    <td colspan="2">Total Expenses</td>
                    <td class="text-red">${{ number_format((float)($data['totalExpenses'] ?? 0), 2) }}</td>
                </tr>
            </tbody>
        </table>
    @else
        <p style="color: #6b7280; font-style: italic; padding: 15px; background: #f9fafb; border-radius: 6px;">
            No expense transactions found in the selected period.
        </p>
    @endif

    <!-- Monthly Trend -->
    <div class="section-title">Monthly Trend (Last 12 Months)</div>
    @if($data['monthlyTrend']->count() > 0)
        <table class="category-table">
            <thead>
                <tr>
                    <th width="30%">Period</th>
                    <th width="25%">Income</th>
                    <th width="25%">Expenses</th>
                    <th width="20%">Net</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['monthlyTrend'] as $trend)
                    @php
                        $monthName = \Carbon\Carbon::create($trend->year, $trend->month, 1)->format('M Y');
                        $net = $trend->income - $trend->expenses;
                    @endphp
                    <tr>
                        <td>{{ $monthName }}</td>
                        <td class="text-green">${{ number_format((float)($trend->income ?? 0), 2) }}</td>
                        <td class="text-red">${{ number_format((float)($trend->expenses ?? 0), 2) }}</td>
                        <td class="{{ $net >= 0 ? 'text-green' : 'text-red' }}">
                            ${{ number_format((float)$net, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #6b7280; font-style: italic; padding: 15px; background: #f9fafb; border-radius: 6px;">
            No monthly trend data available.
        </p>
    @endif

    <!-- Summary Statistics -->
    <div class="section-title">Summary Statistics</div>
    <table style="margin-bottom: 30px;">
        <tr>
            <td width="50%" style="padding: 10px; background: #f3f4f6;">Profit Margin</td>
            <td width="50%" style="padding: 10px;">
                @php
                    $totalIncome = (float)($data['totalIncome'] ?? 0);
                    $netIncome = (float)($data['netIncome'] ?? 0);
                    $margin = $totalIncome > 0
                        ? ($netIncome / $totalIncome) * 100
                        : 0;
                @endphp
                <span class="{{ $margin >= 0 ? 'text-green' : 'text-red' }}">
                    {{ number_format($margin, 1) }}%
                </span>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px; background: #f3f4f6;">Expense Ratio</td>
            <td style="padding: 10px;">
                @php
                    $totalIncome = (float)($data['totalIncome'] ?? 0);
                    $totalExpenses = (float)($data['totalExpenses'] ?? 0);
                    $ratio = $totalIncome > 0
                        ? ($totalExpenses / $totalIncome) * 100
                        : 100;
                @endphp
                {{ number_format($ratio, 1) }}%
            </td>
        </tr>
        <tr>
            <td style="padding: 10px; background: #f3f4f6;">Average Daily Income</td>
            <td style="padding: 10px;">
                @php
                    $days = $startDate->diffInDays($endDate) + 1;
                    $totalIncome = (float)($data['totalIncome'] ?? 0);
                    $dailyIncome = $days > 0 ? $totalIncome / $days : 0;
                @endphp
                ${{ number_format($dailyIncome, 2) }}
            </td>
        </tr>
        <tr>
            <td style="padding: 10px; background: #f3f4f6;">Average Daily Expenses</td>
            <td style="padding: 10px;">
                @php
                    $totalExpenses = (float)($data['totalExpenses'] ?? 0);
                    $dailyExpenses = $days > 0 ? $totalExpenses / $days : 0;
                @endphp
                ${{ number_format($dailyExpenses, 2) }}
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Report generated by {{ auth()->user()->name ?? 'System' }}</p>
        <p>Confidential - For internal use only</p>
        <p>Page 1 of 1</p>
    </div>
</body>
</html>