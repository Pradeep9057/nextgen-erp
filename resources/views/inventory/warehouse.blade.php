<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Stock - NextGen ERP</title>
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
                    <a href="/" class="bg-green-600 text-white p-2 rounded-lg font-black text-xl tracking-tighter">NG</a>
                    <h1 class="text-xl font-bold tracking-tight text-slate-800">Warehouse <span class="text-green-600">Inventory</span></h1>
                </div>
                <a href="{{ route('inventory.index') }}" class="text-sm font-semibold text-slate-600 hover:text-green-600 transition-colors flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0h7m-7 0v-7" />
                    </svg>
                    Back to Hub
                </a>
            </div>
        </header>

        <main class="p-6 lg:p-10 max-w-7xl mx-auto w-full space-y-8">
            <!-- Warehouse Profile -->
            <section class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center text-2xl font-bold">
                        {{ substr($warehouse->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-3xl font-extrabold text-slate-900">{{ $warehouse->name }}</h2>
                        <p class="text-slate-500">{{ $warehouse->location }}</p>
                    </div>
                </div>
                <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 transition-colors">
                    Initiate Stock Take
                </button>
            </section>

            <!-- Stock List -->
            <section>
                <h3 class="text-xl font-bold text-slate-900 mb-6">Current On-Hand Stock</h3>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr class="text-slate-500">
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">Item Name</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">SKU</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider text-right">Available Quantity</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($stock as $row)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4 font-semibold text-slate-900">{{ $row->name }}</td>
                                    <td class="p-4 text-sm text-slate-600 font-mono">{{ $row->sku ?? 'N/A' }}</td>
                                    <td class="p-4 text-right font-bold text-slate-900">{{ number_format($row->total_qty, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-10 text-center text-slate-500">
                                        No stock available in this warehouse.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>