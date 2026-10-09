<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Hub - NextGen ERP</title>
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
                    <a href="/" class="bg-blue-600 text-white p-2 rounded-lg font-black text-xl tracking-tighter">NG</a>
                    <h1 class="text-xl font-bold tracking-tight text-slate-800">CRM <span class="text-blue-600">Hub</span></h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">
                        + New Lead
                    </button>
                    <div class="w-10 h-10 bg-slate-200 rounded-full overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=random" alt="Admin">
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6 lg:p-10 max-w-7xl mx-auto w-full space-y-10">
            <!-- View Selector Bar -->
            <div class="flex flex-wrap items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <select onchange="window.location.href='?view_id=' + this.value" class="appearance-none bg-slate-50 border border-slate-200 text-slate-700 pl-3 pr-8 py-2 rounded-lg text-sm font-medium outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                            <option value="">Select View: <?php echo e($currentView ? $currentView->name : 'All Leads'); ?></option>
                            <?php $__currentLoopData = \App\Modules\CRM\Models\CrmView::where('entity_type', 'Lead')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $view): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($view->id); ?>" <?php echo e(request('view_id') == $view->id ? 'selected' : ''); ?>><?php echo e($view->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    <button onclick="document.getElementById('new-view-modal').classList.remove('hidden')" class="text-xs font-semibold text-blue-600 hover:text-blue-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Save Current as View
                    </button>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="text" placeholder="Quick search..." class="bg-slate-50 border border-slate-200 text-sm rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500 w-64">
                    <button class="bg-slate-100 p-2 rounded-lg text-slate-500 hover:bg-slate-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.707-.293L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Stats Overview -->
            <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <p class="text-sm font-medium text-slate-500 uppercase">Total Leads</p>
                    <p class="text-3xl font-bold text-slate-900"><?php echo e(count($leads)); ?></p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <p class="text-sm font-medium text-slate-500 uppercase">Active Accounts</p>
                    <p class="text-3xl font-bold text-slate-900"><?php echo e(count($accounts)); ?></p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <p class="text-sm font-medium text-slate-500 uppercase">Conversion Rate</p>
                    <p class="text-3xl font-bold text-blue-600">24.8%</p>
                </div>
            </section>

            <!-- Lead Pipeline -->
            <section>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Lead Pipeline</h2>
                    <div class="flex space-x-2">
                        <select class="bg-white border border-slate-300 text-sm rounded-lg p-2 outline-none focus:ring-2 focus:ring-blue-500">
                            <option>All Stages</option>
                            <option>New</option>
                            <option>Contacted</option>
                            <option>Qualified</option>
                            <option>Proposal</option>
                        </select>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Lead Name</th>
                                <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Company</th>
                                <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Stage</th>
                                <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Value</th>
                                <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4">
                                        <div class="font-semibold text-slate-900"><?php echo e($lead->name); ?></div>
                                        <div class="text-xs text-slate-500"><?php echo e($lead->email); ?></div>
                                    </td>
                                    <td class="p-4 text-sm text-slate-600"><?php echo e($lead->company); ?></td>
                                    <td class="p-4">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo e($lead->status === 'qualified' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'); ?>">
                                            <?php echo e(ucfirst($lead->status)); ?>

                                        </span>
                                    </td>
                                    <td class="p-4 text-sm font-medium text-slate-900">$<?php echo e(number_format($lead->estimated_value, 2)); ?></td>
                                    <td class="p-4 text-right">
                                        <a href="<?php echo e(route('crm.lead', $lead->id)); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">View Details</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="p-10 text-center text-slate-500">
                                        No leads found. Start by adding your first lead.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Accounts Section -->
            <section>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight mb-6">Strategic Accounts</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all group">
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H//C/m-12 0H7" />
                                    </svg>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-600 uppercase">Active</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900"><?php echo e($account->name); ?></h3>
                            <p class="text-sm text-slate-500 mt-1 mb-4"><?php echo e($account->industry); ?></p>
                            <a href="<?php echo e(route('crm.account', $account->id)); ?>" class="block text-center bg-slate-100 text-slate-700 py-2 rounded-lg text-sm font-semibold hover:bg-slate-200 transition-colors">
                                Account Dossier
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </section>
        </main>
    </div>
</body>
</html><?php /**PATH /var/www/resources/views/crm/index.blade.php ENDPATH**/ ?>