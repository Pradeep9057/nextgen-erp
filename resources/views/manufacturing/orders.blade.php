<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production Orders - NextGen ERP</title>
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
                    <h1 class="text-xl font-bold tracking-tight text-slate-800">Production <span class="text-orange-600">Orders</span></h1>
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
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Manufacturing Queue</h2>
                <button class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-orange-700 transition-colors">
                    + Start New Production
                </button>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <!-- Order Card -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center font-bold">
                            PO
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">PROD-2026-001</h3>
                            <p class="text-sm text-slate-500">Finished Good: Premium Silver Plate</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-8">
                        <div class="text-center">
                            <p class="text-xs font-medium text-slate-400 uppercase">Status</p>
                            <p class="text-sm font-bold text-blue-600">In Production</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs font-medium text-slate-400 uppercase">Progress</p>
                            <div class="w-32 bg-slate-200 h-2 rounded-full mt-2">
                                <div class="bg-blue-600 h-2 rounded-full w-1/3"></div>
                            </div>
                        </div>
                        <button class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-200 transition-colors">
                            Details
                        </button>
                    </div>
                </div>

                <div class="text-center p-10 text-slate-400 italic">
                    No other active production orders.
                </div>
            </div>
        </main>
    </div>
</body>
</html>