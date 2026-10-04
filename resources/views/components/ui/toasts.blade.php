{{-- Listens for the browser event dispatched by App\Livewire\Concerns\Notifies --}}
<div x-data="{
        toasts: [],
        add(e) {
            const id = Date.now() + Math.random();
            this.toasts.push({ id, message: e.detail.message, type: e.detail.type ?? 'success' });
            setTimeout(() => this.toasts = this.toasts.filter(t => t.id !== id), 4000);
        }
     }"
     x-on:notify.window="add($event)"
     class="pointer-events-none fixed bottom-4 start-4 z-[60] flex w-80 flex-col gap-2">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-transition
             :class="toast.type === 'error' ? 'bg-red-600' : 'bg-green-600'"
             class="pointer-events-auto rounded-md px-4 py-3 text-sm text-white shadow-lg"
             x-text="toast.message"></div>
    </template>
</div>
