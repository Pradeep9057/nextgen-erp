<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Reports - NextGen ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-900">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white border-b border-slate-200 p-4 sticky top-0 z-10">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <a href="/" class="bg-blue-900 text-white p-2 rounded-lg font-black text-xl tracking-tighter">NG</a>
                    <h1 class="text-xl font-bold tracking-tight text-slate-800">Financial <span class="text-blue-900">Intelligence</span></h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="bg-white border border-slate-300 text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-50 transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export PDF
                    </button>
                    <div class="w-10 h-10 bg-slate-200 rounded-full overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=random" alt="Admin">
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6 lg:p-10 max-w-7xl mx-auto w-full space-y-10">
            <!-- Report Navigation -->
            <nav class="flex space-x-4 bg-white p-2 rounded-2xl border border-slate-200 w-fit shadow-sm">
                <a href="{{ route('reports.trial-balance') }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('reports.trial-balance') ? 'bg-blue-900 text-white' : 'text-slate-600 hover:bg-slate-100' }} transition-all">Trial Balance</a>
                <a href="{{ route('reports.profit-loss') }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('reports.profit-loss') ? 'bg-blue-900 text-white' : 'text-slate-600 hover:bg-slate-100' }} transition-all">Profit & Loss</a>
                <a href="{{ route('reports.balance-sheet') }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('reports.balance-sheet') ? 'bg-blue-900 text-white' : 'text-slate-600 hover:bg-slate-100' }} transition-all">Balance Sheet</a>
            </nav>

            <!-- Report Content Area -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                            @if(request()->routeIs('reports.trial-balance')) Trial Balance @elseif(request()->routeIs('reports.profit-loss')) Profit & Loss Statement @else Balance Sheet @endif
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">Generated on {{ now()->format('F d, Y H:i') }} for Organization #1</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-widest">Reporting Period</p>
                        <p class="text-sm font-bold text-slate-700">Fiscal Year 2026</p>
                    </div>
                </div>

                <div class="p-8">
                    @if(request()->routeIs('reports.trial-balance'))
                        <!-- Trial Balance View -->
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-slate-400 text-xs font-semibold uppercase tracking-wider border-b border-slate-100">
                                    <th class="pb-4 px-2">Account Code</th>
                                    <th class="pb-4 px-2">Account Name</th>
                                    <th class="pb-4 px-2 text-right">Debit</th>
                                    <th class="pb-4 px-2 text-right">Credit</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($data as $row)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="py-4 px-2 font-mono text-xs text-slate-500">{{ $row['account_code'] }}</td>
                                        <td class="py-4 px-2 font-medium text-slate-800">{{ $row['account_name'] }}</td>
                                        <td class="py-4 px-2 text-right font-mono text-sm">{{ number_format($row['debit'], 2) }}</td>
                                        <td class="py-4 px-2 text-right font-mono text-sm">{{ number_format($row['credit'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @elseif(request()->routeIs('reports.profit-loss'))
                        <!-- P&L View -->
                        <div class="max-w-2xl mx-auto space-y-6">
                            <div class="flex justify-between items-center p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-slate-600 font-medium">Total Revenue</span>
                                <span class="text-lg font-bold text-slate-900">${{ number_format($data['total_revenue'], 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-slate-600 font-medium">Total Expenses</span>
                                <span class="text-lg font-bold text-red-600">-${{ number_format($data['total_expenses'], 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center p-6 bg-blue-900 text-white rounded-2xl shadow-lg shadow-blue-200">
                                <span class="text-blue-100 font-semibold">Net Profit</span>
                                <span class="text-3xl font-black">${{ number_format($data['net_profit'], 2) }}</span>
                            </div>
                        </div>
                    @else
                        <!-- Balance Sheet View -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            <div class="space-y-6">
                                <h3 class="text-lg font-bold text-slate-900 border-b border-slate-200 pb-2">Assets</h3>
                                <div class="space-y-3">
                                    @foreach($data['assets']['details'] as $asset)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-slate-600">{{ $asset['name'] }}</span>
                                            <span class="font-mono font-medium">${{ number_format($asset['balance'], 2) }}</span>
                                        </div>
                                    @endforeach
                                    <div class="flex justify-between text-base font-bold pt-4 border-t border-slate-200">
                                        <span>Total Assets</span>
                                        <span>${{ number_format($data['assets']['total'], 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <h3 class="text-lg font-bold text-slate-900 border-b border-slate-200 pb-2">Liabilities & Equity</h3>
                                <div class="space-y-3">
                                    @foreach($data['liabilities']['details'] as $liab)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-slate-600">{{ $liab['name'] }}</span>
                                            <span class="font-mono font-medium">${{ number_format($liab['balance'], 2) }}</span>
                                        </div>
                                    @endforeach
                                    <div class="pt-4 border-t border-slate-100 space-y-3">
                                        @foreach($data['equity']['details'] as $eq)
                                            <div class="flex justify-between text-sm">
                                                <span class="text-slate-600">{{ $eq['name'] }}</span>
                                                <span class="font-mono font-medium">${{ number_format($eq['balance'], 2) }}</span>
                                            </div>
                                        @endforeach
                                        <div class="flex justify-between text-sm font-medium text-blue-600 italic">
                                            <span>Retained Earnings (Net Income)</span>
                                            <span>${{ number_format($data['retained_earnings'], 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="flex justify-between text-base font-bold pt-4 border-t border-slate-200">
                                        <span>Total Liab & Equity</span>
                                        <span>${{ number_format($data['equity']['total'], 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>