<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opportunity Pipeline - NextGen ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .kanban-column { min-height: calc(100vh - 250px); }
        .ghost-card { opacity: 0.4; background-color: #e2e8f0; border: 2px dashed #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-900">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white border-b border-slate-200 p-4 sticky top-0 z-10">
            <div class="max-w-full mx-auto flex justify-between items-center px-6">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('crm.index') }}" class="bg-blue-600 text-white p-2 rounded-lg font-black text-xl tracking-tighter">NG</a>
                    <h1 class="text-xl font-bold tracking-tight text-slate-800">Sales <span class="text-blue-600">Pipeline</span></h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">
                        + New Opportunity
                    </button>
                    <div class="w-10 h-10 bg-slate-200 rounded-full overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=random" alt="Admin">
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6 overflow-x-auto">
            <div class="flex space-x-6 h-full min-w-max">
                @foreach($stages as $stage)
                    <div class="w-80 flex-shrink-0 flex flex-col">
                        <div class="flex justify-between items-center mb-4 px-2">
                            <h3 class="font-bold text-slate-700 uppercase tracking-wider text-xs">{{ $stage }}</h3>
                            <span class="bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full text-xs font-bold">
                                {{ count($board[$stage] ?? []) }}
                            </span>
                        </div>
                        <div class="kanban-column space-y-4 p-2 rounded-2xl bg-slate-100/50 border border-slate-200" data-stage="{{ $stage }}">
                            @foreach($board[$stage] ?? [] as $opp)
                                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm cursor-grab active:cursor-grabbing group hover:border-blue-400 transition-all" data-id="{{ $opp->id }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-bold text-slate-900 text-sm leading-tight">{{ $opp->title }}</h4>
                                        <span class="text-xs font-mono font-bold text-blue-600">${{ number_format($opp->estimated_value, 0) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-4">
                                        <div class="flex -space-x-2">
                                            <div class="w-6 h-6 rounded-full bg-slate-200 border-2 border-white flex items-center justify-center text-[10px] font-bold text-slate-500">
                                                {{ substr($opp->account->name ?? 'U', 0, 1) }}
                                            </div>
                                        </div>
                                        <a href="{{ route('crm.opportunity', $opp->id) }}" class="text-xs text-slate-400 hover:text-blue-600 font-medium transition-colors">View Details &rarr;</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </main>
    </div>

    <script>
        document.querySelectorAll('.kanban-column').forEach(column => {
            new Sortable(column, {
                group: 'pipeline',
                animation: 150,
                ghostClass: 'ghost-card',
                onEnd: async (evt) => {
                    const oppId = evt.item.getAttribute('data-id');
                    const newStage = evt.to.getAttribute('data-stage');

                    try {
                        const response = await fetch('{{ route('crm.kanban.move') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                opportunity_id: oppId,
                                new_stage: newStage
                            })
                        });

                        if (!response.ok) {
                            throw new Error('Failed to move opportunity');
                        }
                    } catch (error) {
                        alert('Error updating opportunity stage: ' + error.message);
                        // Revert the card position on failure
                        evt.from.appendChild(evt.item);
                    }
                }
            });
        });
    </script>
</body>
</html>
