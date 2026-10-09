<!-- New View Modal -->
<div id="new-view-modal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900">Save Custom View</h3>
            <button onclick="document.getElementById('new-view-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="save-view-form" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">View Name</label>
                <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., High Value Enterprise Leads">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Sort By</label>
                    <select name="sort_by" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="created_at">Date Created</option>
                        <option value="estimated_value">Value</option>
                        <option value="name">Name</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Order</label>
                    <select name="sort_order" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="desc">Descending</option>
                        <option value="asc">Ascending</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center space-x-2 py-2">
                <input type="checkbox" name="is_default" id="is_default" class="rounded text-blue-600 focus:ring-blue-500">
                <label for="is_default" class="text-sm text-slate-600">Set as default view</label>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl text-sm font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                Save View
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('save-view-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    data.entity_type = 'Lead';
    data.is_default = e.target.is_default.checked;

    try {
        const response = await fetch('{{ route('crm.views.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            window.location.reload();
        } else {
            alert('Failed to save view. Please try again.');
        }
    } catch (error) {
        console.error('Error saving view:', error);
        alert('A system error occurred.');
    }
});
</script>
