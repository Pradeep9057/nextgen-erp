<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead Details - NextGen ERP</title>
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
                    <h1 class="text-xl font-bold tracking-tight text-slate-800">CRM <span class="text-blue-600">Details</span></h1>
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
            <!-- Lead Profile Header -->
            <section class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-2xl font-bold">
                        {{ substr($lead->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-3xl font-extrabold text-slate-900">{{ $lead->name }}</h2>
                        <p class="text-slate-500">{{ $lead->email }} • {{ $lead->company }}</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-200 transition-colors">
                        Edit Lead
                    </button>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">
                        Convert to Account
                    </button>
                </div>
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-8">
                    <section class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="text-lg font-bold text-slate-900 mb-6">Lead Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-medium text-slate-500 uppercase mb-1">Status</p>
                                <p class="text-sm font-semibold text-slate-900">
                                    <span class="px-2 py-1 rounded-full text-xs {{ $lead->status === 'qualified' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ ucfirst($lead->status) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-slate-500 uppercase mb-1">Estimated Value</p>
                                <p class="text-sm font-semibold text-slate-900">${{ number_format($lead->estimated_value, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-slate-500 uppercase mb-1">Industry</p>
                                <p class="text-sm font-semibold text-slate-900">{{ $lead->industry ?? 'Not Specified' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-slate-500 uppercase mb-1">Source</p>
                                <p class="text-sm font-semibold text-slate-900">{{ $lead->source ?? 'Direct' }}</p>
                            </div>
                        </div>
                    </section>

                    <section class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="text-lg font-bold text-slate-900 mb-6">Notes & Timeline</h3>
                        <div class="space-y-4">
                            <div class="flex space-x-4">
                                <div class="text-xs text-slate-400 font-medium w-20 shrink-0">Oct 09, 2026</div>
                                <div class="bg-slate-50 p-3 rounded-lg text-sm text-slate-600 flex-1 border border-slate-100">
                                    Initial outreach completed. Lead is interested in the premium silver line.
                                </div>
                            </div>
                            <div class="flex space-x-4">
                                <div class="text-xs text-slate-400 font-medium w-20 shrink-0">Oct 07, 2026</div>
                                <div class="bg-slate-50 p-3 rounded-lg text-sm text-slate-600 flex-1 border border-slate-100">
                                    Lead entered the pipeline via website contact form.
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Side Panel -->
                <div class="space-y-8">
                    <section class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="text-lg font-bold text-slate-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <button class="w-full text-left px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100 transition-colors flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Send Email
                            </button>
                            <button class="w-full text-left px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100 transition-colors flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-// 0 0 0 0 0 0" />
                                </svg>
                                Schedule Call
                            </button>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
</body>
</html>