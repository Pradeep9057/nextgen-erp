<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Details - NextGen ERP</title>
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
                    <a href="/" class="bg-blue-600 text-white p-2 rounded-lg font-black text-xl tracking-tighter">NG</a>
                    <h1 class="text-xl font-bold tracking-tight text-slate-800">Account <span class="text-blue-600">Dossier</span></h1>
                </div>
                <a href="{{ route('crm.index') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors flex items-center">
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
                    <div class="w-16 h-16 bg-slate-100 text-slate-600 rounded-2xl flex items-center justify-center text-2xl font-bold">
                        {{ substr($account->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-3xl font-extrabold text-slate-900">{{ $account->name }}</h2>
                        <p class="text-slate-500">{{ $account->industry }} • Strategic Partner</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-200 transition-colors">
                        Edit Account
                    </button>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">
                        Create Order
                    </button>
                </div>
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <section class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="text-lg font-bold text-slate-900 mb-6">Corporate Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-medium text-slate-500 uppercase mb-1">Account ID</p>
                                <p class="text-sm font-semibold text-slate-900">ACC-{{ $account->id }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-slate-500 uppercase mb-1">Status</p>
                                <p class="text-sm font-semibold text-slate-900">Active</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-xs font-medium text-slate-500 uppercase mb-1">Industry</p>
                                <p class="text-sm font-semibold text-slate-900">{{ $account->industry }}</p>
                            </div>
                        </div>
                    </section>

                    <section class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="text-lg font-bold text-slate-900 mb-6">Order History</h3>
                        <div class="border border-slate-100 rounded-xl overflow-hidden">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 border-b border-slate-100">
                                    <tr class="text-slate-500 font-medium">
                                        <th class="p-4">Order ID</th>
                                        <th class="p-4">Date</th>
                                        <th class="p-4">Amount</th>
                                        <th class="p-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr>
                                        <td colspan="4" class="p-10 text-center text-slate-400 italic">
                                            No orders associated with this account.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

                <div class="space-y-8">
                    <section class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="text-lg font-bold text-slate-900 mb-4">Relationship Manager</h3>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-slate-200 rounded-full"></div>
                            <div>
                                <p class="text-sm font-bold text-slate-900">Sarah Jenkins</p>
                                <p class="text-xs text-slate-500">Senior Account Exec</p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
</body>
</html>