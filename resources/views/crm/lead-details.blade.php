<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead 360 View - NextGen ERP</title>
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
                        <a href="{{ route('crm.index') }}" class="hover:text-blue-600">Leads</a>
                        <span>&rarr;</span>
                        <span class="text-slate-900">{{ $lead360['profile']->first_name }} {{ $lead360['profile']->last_name }}</span>
                    </nav>
                </div>
                <div class="flex items-center space-x-3">
                    <button class="text-sm font-semibold text-slate-600 px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">Edit Lead</button>
                    <button class="text-sm font-semibold bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">Convert to Opportunity</button>
                </div>
            </div>
        </header>

        <main class="p-6 max-w-7xl mx-auto w-full grid grid-cols-12 gap-6">
            <!-- Left Column: Profile & Metrics -->
            <div class="col-span-12 lg:col-span-4 space-y-6">
                <!-- Profile Card -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="h-24 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                    <div class="px-6 pb-6">
                        <div class="relative -mt-12 mb-4">
                            <div class="w-20 h-20 rounded-2xl bg-white p-1 shadow-md">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($lead360['profile']->first_name . ' ' . $lead360['profile']->last_name) }}&background=random" class="w-full h-full rounded-xl object-cover" alt="Avatar">
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900">{{ $lead360['profile']->first_name }} {{ $lead360['profile']->last_name }}</h2>
                        <p class="text-slate-500 text-sm mb-6">{{ $lead360['profile']->job_title }} at {{ $lead360['profile']->company }}</p>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <p class="text-[10px] uppercase font-bold text-slate-400 mb-1">Status</p>
                                <span class="text-sm font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 capitalize">{{ $lead360['profile']->status }}</span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <p class="text-[10px] uppercase font-bold text-slate-400 mb-1">Lead Source</p>
                                <span class="text-sm font-semibold text-slate-700">{{ $lead360['profile']->source ?? 'Direct' }}</span>
                            </div>
                        </div>

                        <div class="space-y-3 border-t border-slate-100 pt-6">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-500">Email</span>
                                <span class="font-medium text-slate-900">{{ $lead360['profile']->email }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-500">Phone</span>
                                <span class="font-medium text-slate-900">{{ $lead360['profile']->phone }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-500">Location</span>
                                <span class="font-medium text-slate-900">{{ $lead360['profile']->city }}, {{ $lead360['profile']->country }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AI Insights / Metrics -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Predictive Metrics
                    </h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div class="metric-card p-4 rounded-xl bg-blue-50 border border-blue-100 flex justify-between items-center">
                            <div>
                                <p class="text-xs font-medium text-blue-600 mb-1">Lead Score</p>
                                <p class="text-2xl font-bold text-blue-900">{{ $lead360['metrics']['lead_score'] }}<span class="text-sm font-normal text-blue-400 ml-1">/100</span></p>
                            </div>
                            <div class="w-12 h-12 rounded-full border-4 border-blue-200 border-t-blue-600 flex items-center justify-center text-xs font-bold text-blue-600">
                                {{ round(($lead360['metrics']['lead_score'] / 100) * 100) }}%
                            </div>
                        </div>
                        <div class="metric-card p-4 rounded-xl bg-indigo-50 border border-indigo-100 flex justify-between items-center">
                            <div>
                                <p class="text-xs font-medium text-indigo-600 mb-1">Engagement</p>
                                <p class="text-lg font-bold text-indigo-900">{{ $lead360['metrics']['engagement_level'] }}</p>
                            </div>
                            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 12h18"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Activities & Related -->
            <div class="col-span-12 lg:col-span-8 space-y-6">
                <!-- Tabs -->
                <div class="flex space-x-1 bg-slate-200/50 p-1 rounded-xl w-fit">
                    <button class="px-4 py-2 text-sm font-semibold rounded-lg bg-white text-slate-900 shadow-sm">Timeline</button>
                    <button class="px-4 py-2 text-sm font-medium rounded-lg text-slate-600 hover:text-slate-900 transition-colors">Opportunities</button>
                    <button class="px-4 py-2 text-sm font-medium rounded-lg text-slate-600 hover:text-slate-900 transition-colors">Documents</button>
                </div>

                <!-- Activity Timeline Placeholder -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-900">Activity Timeline</h3>
                        <button class="text-sm font-semibold text-blue-600 hover:text-blue-700">+ Log Activity</button>
                    </div>

                    <div class="relative space-y-8 before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-blue-500 before:via-slate-200 before:to-transparent">
                        <!-- Activity Item 1 (Demo) -->
                        <div class="relative pl-12">
                            <div class="absolute left-0 top-1 w-10 h-10 rounded-full bg-white border-2 border-blue-500 flex items-center justify-center z-10">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.5 7.82 3 3 0 01-3 3H5a2 2 0 01-2-2V5z"/></svg>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <div class="flex justify-between items-start mb-1">
                                    <p class="text-sm font-bold text-slate-900">Discovery Call</p>
                                    <span class="text-[10px] font-medium text-slate-400">2 hours ago</span>
                                </div>
                                <p class="text-sm text-slate-600">Discussed quarterly requirements. Lead is interested in Enterprise tier.</p>
                                <div class="mt-2 flex items-center text-xs text-slate-400">
                                    <img src="https://ui-avatars.com/api/?name=Sales+Rep&background=random" class="w-4 h-4 rounded-full mr-2">
                                    Logged by Sales Rep
                                </div>
                            </div>
                        </div>

                        <!-- Activity Item 2 (Demo) -->
                        <div class="relative pl-12">
                            <div class="absolute left-0 top-1 w-10 h-10 rounded-full bg-white border-2 border-slate-300 flex items-center justify-center z-10">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <div class="flex justify-between items-start mb-1">
                                    <p class="text-sm font-bold text-slate-900">Email Sent</p>
                                    <span class="text-[10px] font-medium text-slate-400">Yesterday</span>
                                </div>
                                <p class="text-sm text-slate-600">Sent introductory brochure and pricing sheet.</p>
                                <div class="mt-2 flex items-center text-xs text-slate-400">
                                    <img src="https://ui-avatars.com/api/?name=Sales+Rep&background=random" class="w-4 h-4 rounded-full mr-2">
                                    Logged by Sales Rep
                                </div>
                            </div>
                        </div>

                        <!-- Empty State for actual data -->
                        @if(empty($lead360['activities']))
                            <div class="text-center py-10 px-6 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                                <p class="text-slate-400 text-sm italic">No further activities recorded. Log your first interaction to get started.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Related Opportunities -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Related Opportunities</h3>
                    @if(count($lead360['related']['opportunities']) > 0)
                        <div class="overflow-hidden border border-slate-100 rounded-xl">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-100">
                                    <tr>
                                        <th class="px-4 py-3">Opportunity Name</th>
                                        <th class="px-4 py-3">Stage</th>
                                        <th class="px-4 py-3 text-right">Value</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($lead360['related']['opportunities'] as $opp)
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
                            <p class="text-slate-400 text-sm italic">No associated opportunities found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>
