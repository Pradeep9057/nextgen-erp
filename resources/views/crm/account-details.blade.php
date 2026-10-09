<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account 360 View - NextGen ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .metric-card { transition: all 0.2s ease; }
        .metric-card:hover { transform: translateY(-2px); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">
    <div class="min-h-screen flex flex-col">
        <!-- Top Navigation -->
        <header class="bg-white border-b border-slate-200 p-4 sticky top-0 z-10">
            <div class="max-w-full mx-auto flex justify-between items-center px-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('crm.index') }}" class="bg-blue-600 text-white p-2 rounded-lg font-black text-xl tracking-tighter">NG</a>
                    <nav class="flex items-center text-sm font-medium text-slate-500 space-x-2">
                        <a href="{{ route('crm.index') }}" class="hover:text-blue-600">Accounts</a>
                        <span>&rarr;</span>
                        <span class="text-slate-900">{{ $account360['profile']->name }}</span>
                    </nav>
                </div>
                <div class="flex items-center space-x-3">
                    <button class="text-sm font-semibold text-slate-600 px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">Edit Account</button>
                    <button class="text-sm font-semibold bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">Create Opportunity</button>
                </div>
            </div>
        </header>

        <main class="p-6 max-w-7xl mx-auto w-full grid grid-cols-12 gap-6">
            <!-- Left Column: Profile & Metrics -->
            <div class="col-span-12 lg:col-span-4 space-y-6">
                <!-- Profile Card -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="h-24 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                    <div class="px-6 pb-6">
                        <div class="relative -mt-12 mb-4">
                            <div class="w-20 h-20 rounded-2xl bg-white p-1 shadow-md">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($account360['profile']->name) }}&background=random" class="w-full h-full rounded-xl object-cover" alt="Avatar">
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900">{{ $account360['profile']->name }}</h2>
                        <p class="text-slate-500 text-sm mb-6">{{ $account360['profile']->industry }} &bull; {{ $account360['profile']->employee_count ?? 'N/A' }} employees</p>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <p class="text-[10px] uppercase font-bold text-slate-400 mb-1">Tier</p>
                                <span class="text-sm font-semibold px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 capitalize">Enterprise</span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <p class="text-[10px] uppercase font-bold text-slate-400 mb-1">Account Mgr</p>
                                <span class="text-sm font-semibold text-slate-700">Sarah Connor</span>
                            </div>
                        </div>

                        <div class="space-y-3 border-t border-slate-100 pt-6">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-500">Website</span>
                                <span class="font-medium text-blue-600">{{ $account360['profile']->website }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-500">Primary Phone</span>
                                <span class="font-medium text-slate-900">{{ $account360['profile']->phone }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-500">HQ Location</span>
                                <span class="font-medium text-slate-900">{{ $account360['profile']->city }}, {{ $account360['profile']->country }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Health / Metrics -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Account Health
                    </h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div class="metric-card p-4 rounded-xl bg-indigo-50 border border-indigo-100 flex justify-between items-center">
                            <div>
                                <p class="text-xs font-medium text-indigo-600 mb-1">Lifetime Value (CLV)</p>
                                <p class="text-2xl font-bold text-indigo-900">${{ number_format($account360['metrics']['customer_lifetime_value'], 2) }}</p>
                            </div>
                            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="metric-card p-4 rounded-xl bg-emerald-50 border border-emerald-100 flex justify-between items-center">
                            <div>
                                <p class="text-xs font-medium text-emerald-600 mb-1">Health Score</p>
                                <p class="text-lg font-bold text-emerald-900">{{ $account360['metrics']['health_score'] }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-emerald-200 flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Contacts & Opportunities -->
            <div class="col-span-12 lg:col-span-8 space-y-6">
                <!-- Tabs -->
                <div class="flex space-x-1 bg-slate-200/50 p-1 rounded-xl w-fit">
                    <button class="px-4 py-2 text-sm font-semibold rounded-lg bg-white text-slate-900 shadow-sm">Contacts</button>
                    <button class="px-4 py-2 text-sm font-medium rounded-lg text-slate-600 hover:text-slate-900 transition-colors">Opportunities</button>
                    <button class="px-4 py-2 text-sm font-medium rounded-lg text-slate-600 hover:text-slate-900 transition-colors">Financials</button>
                </div>

                <!-- Contacts List -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-900">Key Contacts</h3>
                        <button class="text-sm font-semibold text-blue-600 hover:text-blue-700">+ Add Contact</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($account360['contacts'] as $contact)
                            <div class="flex items-center p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors group">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($contact->first_name . ' ' . $contact->last_name) }}&background=random" class="w-10 h-10 rounded-full mr-3" alt="Contact">
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $contact->first_name }} {{ $contact->last_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $contact->job_title }}</p>
                                </div>
                                <a href="{{ route('crm.lead', $contact->id) }}" class="text-slate-300 hover:text-blue-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        @empty
                            <div class="col-span-2 text-center py-10 px-6 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                                <p class="text-slate-400 text-sm italic">No contacts associated with this account.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Related Opportunities -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Opportunities Pipeline</h3>
                    @if(count($account360['opportunities']) > 0)
                        <div class="overflow-hidden border border-slate-100 rounded-xl">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-100">
                                    <tr class="border-b">
                                        <th class="px-4 py-3">Opportunity Name</th>
                                        <th class="px-4 py-3">Stage</th>
                                        <th class="px-4 py-3 text-right">Value</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($account360['opportunities'] as $opp)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-4 py-3 font-medium text-slate-900">{{ $opp->title }}</td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">{{ $opp->stage }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-right font-mono font-bold text-slate-900">${{ number_format($opp->estimated_value, 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10 px-6 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                            <p class="text-slate-400 text-sm italic">No open opportunities found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>
