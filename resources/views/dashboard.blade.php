<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextGen ERP - Executive Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-900">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <header class="bg-white border-b border-slate-200 p-4 sticky top-0 z-10">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-600 text-white p-2 rounded-lg font-black text-xl tracking-tighter">NG</div>
                    <h1 class="text-xl font-bold tracking-tight text-slate-800">NextGen <span class="text-blue-600">ERP</span></h1>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="text-right hidden md:block">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">System Status</p>
                        <p class="text-sm font-semibold text-green-600 flex items-center justify-end">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                            Operational
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-slate-200 rounded-full border-2 border-white shadow-sm overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=random" alt="Admin">
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6 lg:p-10 max-w-7xl mx-auto w-full space-y-10">
            <!-- Welcome Section -->
            <section>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Executive Overview</h2>
                <p class="text-slate-500 mt-1">Welcome back. Here is what's happening across your enterprise today.</p>
            </section>

            <!-- KPI Grid -->
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Revenue Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden group transition-all hover:shadow-md">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Revenue</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">${{ number_format($kpis['total_revenue'], 2) }}</p>
                    <div class="mt-4 flex items-center text-xs font-medium text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L6.586 7.293V14a1 1 0 01-2 0V9.707z" clip-rule="evenodd" />
                        </svg>
                        <span>+12.5% from last month</span>
                    </div>
                </div>

                <!-- Leads Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden group transition-all hover:shadow-md">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-1.283-.356-1.857M7 20H2v-2a3 3 0 00-5.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0h10" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Active Leads</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $kpis['active_leads'] }}</p>
                    <div class="mt-4 flex items-center text-xs font-medium text-blue-600">
                        <span>View pipeline &rarr;</span>
                    </div>
                </div>

                <!-- Stock Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden group transition-all hover:shadow-md">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Low Stock Alert</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ $kpis['low_stock_items'] }}</p>
                    <div class="mt-4 flex items-center text-xs font-medium text-red-500">
                        <span>Immediate action required</span>
                    </div>
                </div>

                <!-- Orders Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden group transition-all hover:shadow-md">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 000 2h12a2 2 0 000-2H9z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Pending Orders</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $kpis['pending_orders'] }}</p>
                    <div class="mt-4 flex items-center text-xs font-medium text-blue-600">
                        <span>Process queue &rarr;</span>
                    </div>
                </div>
            </section>

            <!-- Module Hub -->
            <section>
                <div class="flex justify-between items-end mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Enterprise Hub</h2>
                        <p class="text-slate-500">Fast access to core business systems.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- CRM & Sales -->
                    <a href="/crm" class="group p-6 bg-white rounded-2xl shadow-sm border border-slate-200 hover:border-blue-500 hover:shadow-md transition-all duration-200">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-1.283-.356-1.857M7 20H2v-2a3 3 0 00-5.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0h10" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600">CRM & Sales</h3>
                        <p class="text-sm text-slate-500 mt-2">Manage leads, quotations, and sales orders.</p>
                    </a>

                    <!-- Inventory -->
                    <a href="/inventory" class="group p-6 bg-white rounded-2xl shadow-sm border border-slate-200 hover:border-green-500 hover:shadow-md transition-all duration-200">
                        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-green-600 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-green-600">Inventory</h3>
                        <p class="text-sm text-slate-500 mt-2">Immutable ledger, stock tracking, and warehouses.</p>
                    </a>

                    <!-- TrustPath -->
                    <a href="/integrity" class="group p-6 bg-white rounded-2xl shadow-sm border border-slate-200 hover:border-purple-500 hover:shadow-md transition-all duration-200">
                        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04 l11.955 11.955 0 01-2.015 10.144a11.955 11.955 0 0110.144 10.144 11.955 11.955 0 0110.144-10.144a11.955 11.955 0 01-2.015-10.144z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-purple-600">TrustPath</h3>
                        <p class="text-sm text-slate-500 mt-2"> Blockchain anchoring and tamper detection.</p>
                    </a>

                    <!-- Customization -->
                    <a href="/customization" class="group p-6 bg-white rounded-2xl shadow-sm border border-slate-200 hover:border-yellow-500 hover:shadow-md transition-all duration-200">
                        <div class="w-12 h-12 bg-yellow-50 text-yellow-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-yellow-600 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-yellow-600">Customization</h3>
                        <p class="text-sm text-slate-500 mt-2">EAV Dynamic Studio for custom fields.</p>
                    </a>
                </div>
            </section>
        </main>
    </div>
</body>
</html>