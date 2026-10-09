<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF, charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customization Studio - NextGen ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .drag-over { border: 2px dashed #eab308; background-color: #fefce8; }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-900">
    <div class="min-h-screen flex flex-col">
        <!-- Top Navigation -->
        <header class="bg-white border-b border-slate-200 p-4 sticky top-0 z-10">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <a href="/" class="bg-yellow-500 text-white p-2 rounded-lg font-black text-xl tracking-tighter">NG</a>
                    <h1 class="text-xl font-bold tracking-tight text-slate-800">Customization <span class="text-yellow-600">Studio</span></h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-600 transition-colors">
                        Save Configuration
                    </button>
                    <div class="w-10 h-10 bg-slate-200 rounded-full overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=random" alt="Admin">
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6 lg:p-10 max-w-7xl mx-auto w-full space-y-10">
            <!-- Studio Header -->
            <section>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Studio Workspace</h2>
                <p class="text-slate-500 mt-1">Configure your ERP's DNA. Add custom fields and define business rules without writing code.</p>
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Tool Palette (Draggable Elements) -->
                <div class="lg:col-span-3 space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Field Toolbox</h3>
                        <div class="space-y-3">
                            <div draggable="true" class="p-3 bg-slate-50 border border-slate-200 rounded-xl cursor-move hover:border-yellow-500 transition-all flex items-center space-x-3">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                <span class="text-sm font-medium text-slate-700">Text Input</span>
                            </div>
                            <div draggable="true" class="p-3 bg-slate-50 border border-slate-200 rounded-xl cursor-move hover:border-yellow-500 transition-all flex items-center space-x-3">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <span class="text-sm font-medium text-slate-700">Numeric Field</span>
                            </div>
                            <div draggable="true" class="p-3 bg-slate-50 border border-slate-200 rounded-xl cursor-move hover:border-yellow-500 transition-all flex items-center space-x-3">
                                <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                <span class="text-sm font-medium text-slate-700">Date Picker</span>
                            </div>
                            <div draggable="true" class="p-3 bg-slate-50 border border-slate-200 rounded-xl cursor-move hover:border-yellow-500 transition-all flex items-center space-x-3">
                                <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                                <span class="text-sm font-medium text-slate-700">Dropdown List</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Logic Gates</h3>
                        <div class="space-y-3">
                            <div draggable="true" class="p-3 bg-slate-50 border border-slate-200 rounded-xl cursor-move hover:border-yellow-500 transition-all flex items-center space-x-3">
                                <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                <span class="text-sm font-medium text-slate-700 font-mono">AND ()</span>
                            </div>
                            <div draggable="true" class="p-3 bg-slate-50 border border-slate-200 rounded-xl cursor-move hover:border-yellow-500 transition-all flex items-center space-x-3">
                                <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                <span class="text-sm font-medium text-slate-700 font-mono">OR ()</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Canvas (Drop Zone) -->
                <div class="lg:col-span-9 space-y-8">
                    <!-- Field Designer -->
                    <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200 p-8 min-h-[400px] relative transition-all drag-over" id="canvas">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="text-lg font-bold text-slate-900">Entity Designer: <span class="text-blue-600">CRM Lead</span></h3>
                            <div class="flex space-x-2">
                                <button class="text-xs font-semibold text-slate-500 px-3 py-1 rounded-full bg-slate-100 hover:bg-slate-200">Clear All</button>
                                <button class="text-xs font-semibold text-blue-600 px-3 py-1 rounded-full bg-blue-50 hover:bg-blue-100">Add New Group</button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="drop-zone">
                            @forelse($fields as $field)
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl flex justify-between items-center group">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 rounded-full bg-slate-400"></div>
                                        <span class="text-sm font-medium text-slate-700">{{ $field->name }}</span>
                                    </div>
                                    <button class="text-slate-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @empty
                                <div class="col-span-2 py-20 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <p class="text-slate-400">Drag fields from the toolbox here to customize your entity</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Recursive Rule Builder -->
                    <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
                        <h3 class="text-lg font-bold text-slate-900 mb-6">Visual Rule Builder</h3>
                        <div class="p-6 bg-slate-900 rounded-2xl text-white font-mono text-sm relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-1 h-full bg-yellow-500"></div>
                            <div class="space-y-4">
                                <div class="flex items-center space-x-2">
                                    <span class="text-yellow-400">IF</span>
                                    <span class="text-blue-300">(</span>
                                    <span class="bg-slate-800 px-2 py-1 rounded border border-slate-700">Lead.Value &gt; 10000</span>
                                    <span class="text-red-400 font-bold">AND</span>
                                    <span class="bg-slate-800 px-2 py-1 rounded border border-slate-700">Lead.Industry == 'Enterprise'</span>
                                    <span class="text-blue-300">)</span>
                                </div>
                                <div class="flex items-center space-x-2 ml-6">
                                    <span class="text-green-400">THEN</span>
                                    <span class="bg-slate-800 px-2 py-1 rounded border border-slate-700">Set Status = 'Qualified'</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end space-x-3">
                            <button class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900">Reset Logic</button>
                            <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-600 transition-colors">Test Rule</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>