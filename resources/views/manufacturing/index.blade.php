<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manufacturing Hub - NextGen ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-900">
    <div class="min-h-screen flex flex-col">
        <!-- Top Navigation -->
        <header class="bg-white border-b border-slate-200 p-4 sticky top-0 z-10">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <a href="/" class="bg-orange-600 text-white p-2 rounded-lg font-black text-xl tracking-tighter">NG</a>
                    <h1 class="text-xl font-bold tracking-tight text-slate-800">Manufacturing <span class="text-orange-600">Hub</span></h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-orange-700 transition-colors">
                        + New BOM
                    </button>
                    <div class="w-10 h-10 bg-slate-200 rounded-full overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=random" alt="Admin">
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6 lg:p-10 max-w-7xl mx-auto w-full space-y-10">
            <!-- Work Centers -->
            <section>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight mb-6">Active Work Centers</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($workCenters as $wc)
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">{{ $wc->name }}</h3>
                                <p class="text-sm text-slate-500">Status: <span class="text-green-600 font-medium">Running</span></p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.//C/m-1.543-1.066a1.724 1.724 0 00-2.573-1.066z" />
                                </svg>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- BOM List -->
            <section>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Bill of Materials (BOM)</h2>
                    <a href="{{ route('manufacturing.orders') }}" class="text-orange-600 hover:text-orange-800 text-sm font-bold flex items-center">
                        View Production Orders &rarr;
                    </a>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr class="text-slate-500">
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">Finished Good</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">Complexity</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">Components</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($boms as $bom)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4">
                                        <div class="font-semibold text-slate-900">{{ $bom->finished_good_id }}</div>
                                        <div class="text-xs text-slate-500">Version: {{ $bom->version }}</div>
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-600">
                                            Standard
                                        </span>
                                    </td>
                                    <td class="p-4 text-sm text-slate-600">{{ $bom->items->count() }} Components</td>
                                    <td class="p-4 text-right">
                                        <a href="{{ route('manufacturing.bom', $bom->id) }}" class="text-orange-600 hover:text-orange-800 text-sm font-semibold">Edit BOM</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-10 text-center text-slate-500">
                                        No BOMs defined. Start by creating a production recipe.
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