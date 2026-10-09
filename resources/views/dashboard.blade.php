<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextGen ERP - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white shadow-sm p-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">NextGen ERP Dashboard</h1>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-500">System Status: <span class="text-green-500 font-bold">Online</span></span>
                <div class="w-8 h-8 bg-blue-500 rounded-full"></div>
            </div>
        </header>

        <main class="p-8 max-w-6xl mx-auto w-full">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Enterprise Modules</h2>
                <p class="text-gray-600">Select a module to manage your business operations.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- CRM & Sales -->
                <a href="/crm" class="group p-6 bg-white rounded-2xl shadow-sm border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all duration-200">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 300005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0h10" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600">CRM & Sales</h3>
                    <p class="text-sm text-gray-500 mt-2">Lead-to-Cash verified.</p>
                </a>

                <!-- Inventory -->
                <a href="/inventory" class="group p-6 bg-white rounded-2xl shadow-sm border border-gray-200 hover:border-green-500 hover:shadow-md transition-all duration-200">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-green-600 group-hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-green-600">Inventory</h3>
                    <p class="text-sm text-gray-500 mt-2">Immutable Ledger active.</p>
                </a>

                <!-- TrustPath -->
                <a href="/integrity" class="group p-6 bg-white rounded-2xl shadow-sm border border-gray-200 hover:border-purple-500 hover:shadow-md transition-all duration-200">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04 la 11.955 11.955 0 01-2.015 10.144a11.955 11.955 0 0110.144 10.144 11.955 11.955 0 0110.144-10.144a11.955 11.955 0 01-2.015-10.144z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-purple-600">TrustPath</h3>
                    <p class="text-sm text-gray-500 mt-2">Tamper detection active.</p>
                </a>

                <!-- Customization -->
                <a href="/customization" class="group p-6 bg-white rounded-2L shadow-sm border border-gray-200 hover:border-yellow-500 hover:shadow-md transition-all duration-200">
                    <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-yellow-600 group-hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-yellow-600">Customization</h3>
                    <p class="text-sm text-gray-500 mt-2">Dynamic Studio ready.</p>
                </a>
            </div>
        </main>
    </div>
</body>
</html>