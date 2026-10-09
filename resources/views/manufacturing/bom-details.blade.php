<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOM Editor - NextGen ERP</title>
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
                    <a href="/" class="bg-orange-600 text-white p-2 rounded-lg font-black text-xl tracking-tighter">NG</a>
                    <h1 class="text-xl font-bold tracking-tight text-slate-800">BOM <span class="text-orange-600">Studio</span></h1>
                </div>
                <a href="{{ route('manufacturing.index') }}" class="text-sm font-semibold text-slate-600 hover:text-orange-600 transition-colors flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0h7m-7 0v-7" />
                    </svg>
                    Back to Hub
                </a>
            </div>
        </header>

        <main class="p-6 lg:p-10 max-w-7xl mx-auto w-full space-y-8">
            <section class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center text-2xl font-bold">
                        BOM
                    </div>
                    <div>
                        <h2 class="text-3xl font-extrabold text-slate-900">Finished Good #{{ $bom->finished_good_id }}</h2>
                        <p class="text-slate-500">Version: {{ $bom->version }} • Active Recipe</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-200 transition-colors">
                        Save Changes
                    </button>
                    <button class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-orange-700 transition-colors">
                        Release Version
                    </button>
                </div>
            </section>

            <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-900">Component Breakdown</h3>
                    <button class="text-orange-600 hover:text-orange-800 text-sm font-bold">+ Add Component</button>
                </div>
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr class="text-slate-500">
                            <th class="p-4 text-xs font-semibold uppercase tracking-wider">Item SKU</th>
                            <th class="p-4 text-xs font-semibold uppercase tracking-wider">Required Qty</th>
                            <th class="p-4 text-xs font-semibold uppercase tracking-wider">UOM</th>
                            <th class="p-4 text-xs font-semibold uppercase tracking-wider">Waste %</th>
                            <th class="p-4 text-xs font-semibold uppercase tracking-wider text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($items as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="p-4 font-mono text-sm text-slate-600">{{ $item->inventory_item_id }}</td>
                                <td class="p-4">
                                    <input type="number" value="{{ $item->quantity }}" class="bg-slate-50 border border-slate-200 rounded px-2 py-1 text-sm w-24 outline-none focus:ring-2 focus:ring-orange-500">
                                </td>
                                <td class="p-4 text-sm text-slate-600">pcs</td>
                                <td class="p-4">
                                    <input type="number" value="{{ $item->waste_percentage }}" class="bg-slate-50 border border-slate-200 rounded px-2 py-1 text-sm w-20 outline-none focus:ring-2 focus:ring-orange-500">
                                </td>
                                <td class="p-4 text-right">
                                    <button class="text-red-500 hover:text-red-700 text-sm font-semibold">Remove</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-slate-500">
                                    No components defined for this BOM.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>