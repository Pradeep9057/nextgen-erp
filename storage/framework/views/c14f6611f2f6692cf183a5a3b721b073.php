<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Hub - NextGen ERP</title>
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
                    <a href="/" class="bg-green-600 text-white p-2 rounded-lg font-black text-xl tracking-tighter">NG</a>
                    <h1 class="text-xl font-bold tracking-tight text-slate-800">Inventory <span class="text-green-600">Hub</span></h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 transition-colors">
                        + Add Item
                    </button>
                    <div class="w-10 h-10 bg-slate-200 rounded-full overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=random" alt="Admin">
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6 lg:p-10 max-w-7xl mx-auto w-full space-y-10">
            <!-- Warehouse Overview -->
            <section>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight mb-6">Warehouses & Locations</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('inventory.warehouse', $warehouse->id)); ?>" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:border-green-500 hover:shadow-md transition-all group">
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H7m14 0h2m-2 0h-5m-8 0H3m2 0h14" />
                                    </svg>
                                </div>
                                <span class="text-xs font-medium text-slate-400 uppercase tracking-widest">Location</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 group-hover:text-green-600"><?php echo e($warehouse->name); ?></h3>
                            <p class="text-sm text-slate-500 mt-1 mb-4"><?php echo e($warehouse->location); ?></p>
                            <span class="text-xs font-semibold text-green-600 flex items-center">
                                Explore Stock &rarr;
                            </span>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </section>

            <!-- Items Master List -->
            <section>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Items Master List</h2>
                    <div class="flex space-x-2">
                        <input type="text" placeholder="Search SKU or Name..." class="bg-white border border-slate-300 text-sm rounded-lg p-2 outline-none focus:ring-2 focus:ring-green-500 w-64">
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr class="text-slate-500">
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">SKU</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">Item Name</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">Category</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">UOM</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider text-right">Ledger</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4 font-mono text-xs text-slate-600"><?php echo e($item->sku); ?></td>
                                    <td class="p-4">
                                        <div class="font-semibold text-slate-900"><?php echo e($item->name); ?></div>
                                        <div class="text-xs text-slate-500"><?php echo e($item->description); ?></div>
                                    </td>
                                    <td class="p-4 text-sm text-slate-600"><?php echo e($item->category->name ?? 'Uncategorized'); ?></td>
                                    <td class="p-4 text-sm text-slate-600"><?php echo e($item->uom->name ?? 'N/A'); ?></td>
                                    <td class="p-4 text-right">
                                        <a href="<?php echo e(route('inventory.ledger', $item->id)); ?>" class="text-green-600 hover:text-green-800 text-sm font-semibold">View History</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="p-10 text-center text-slate-500">
                                        No items found in the master list.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html><?php /**PATH /var/www/resources/views/inventory/index.blade.php ENDPATH**/ ?>