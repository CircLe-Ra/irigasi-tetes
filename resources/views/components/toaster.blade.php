@persist('toaster')
<div
    x-data="{
        toasts: [],
        actionToast: null,
        showActionToast: false,

        addToast(event) {
            const toast = {
                id: Date.now(),
                type: event.detail.type || 'success',
                message: event.detail.message,
                duration: event.detail.duration || 3000,
                element: null
            };

            this.toasts.unshift(toast);

            setTimeout(() => {
                this.removeToast(toast.id);
            }, toast.duration);
        },

        async removeToast(id) {
            const index = this.toasts.findIndex(t => t.id === id);
            if (index !== -1) {
                const toast = this.toasts[index];
                if (toast.element) {
                    await window.animate(
                        toast.element,
                        { opacity: 0, y: 20 },
                        { duration: 0.3,  easing: [0.16, 1, 0.3, 1] }
                    ).finished;
                }
                this.toasts = this.toasts.filter(t => t.id !== id);
            }
        },

        initToast(element, id) {
            const toast = this.toasts.find(t => t.id === id);
            if (toast) {
                toast.element = element;
                window.animate(
                    element,
                    { opacity: [0, 1], y: [20, 0] },
                    { duration: 0.3,  easing: [0.16, 1, 0.3, 1] }
                );
            }
        },

        addToastAction(event) {
         console.log('test')
            this.actionToast = {
                id: Date.now(),
                message: event.detail.message || 'Perubahan belum disimpan',
                label: event.detail.label || 'Simpan perubahan sekarang?',
                onConfirm: event.detail.onConfirm || (() => {}),
                onCancel: event.detail.onCancel || (() => {}),
                element: null
            };

            this.showActionToast = true;
        },

        async removeActionToast() {
            if (this.actionToast?.element) {
                await window.animate(
                    this.actionToast.element,
                    { opacity: 0, y: 20 },
                    { duration: 0.3, easing: [0.16, 1, 0.3, 1] }
                ).finished;
            }
            this.showActionToast = false;
            this.actionToast = null;
        },

        initActionToast(element) {
            if (this.actionToast) {
                this.actionToast.element = element;
                window.animate(
                    element,
                    { opacity: [0, 1], y: [20, 0] },
                    { duration: 0.3, easing: [0.16, 1, 0.3, 1] }
                );
            }
        },

        confirmAction() {
            if (this.actionToast?.onConfirm) {
                this.actionToast.onConfirm();
            }
            this.removeActionToast();
        },

        cancelAction() {
            if (this.actionToast?.onCancel) {
                this.actionToast.onCancel();
            }
            this.removeActionToast();
        }
    }"
    x-on:toast.window="addToast($event)"
    x-on:action-toast.window="addToastAction($event)"
    x-on:action-toast-closed.window="removeActionToast"
    class="fixed bottom-10 left-1/2 transform -translate-x-1/2 space-y-3 w-[calc(100%-20px)] md:w-full md:max-w-md pointer-events-none"
>
    <!-- Toaster Biasa -->
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-ref="toastElement"
            x-init="initToast($refs.toastElement, toast.id)"
            class="pointer-events-auto w-full overflow-hidden rounded-lg shadow-lg"
            :class="{
                'bg-zinc-900 text-zinc-100': toast.type === 'success' || toast.type === 'error',
                'bg-blue-50 text-blue-800': toast.type === 'info',
                'bg-yellow-50 text-yellow-800': toast.type === 'warning'
            }"
        >
            <div class="relative p-6 flex items-center">
                <div class="flex-shrink-0">
                    <template x-if="toast.type === 'success'">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </template>
                    <template x-if="toast.type === 'info'">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                        </svg>
                    </template>
                    <template x-if="toast.type === 'warning'">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </template>
                </div>
                <div class="ml-3">
                    <p x-text="toast.message" class="text-sm font-medium"></p>
                </div>
                <div class="absolute ml-4 flex-shrink-0 right-3 mt-1.5">
                    <button
                        @click="removeToast(toast.id)"
                        class="inline-flex rounded-md focus:outline-none cursor-pointer"
                        :class="{
                            'text-zinc-400 hover:text-zinc-500': toast.type === 'success' || toast.type === 'error',
                            'text-blue-400 hover:text-blue-500': toast.type === 'info',
                            'text-yellow-400 hover:text-yellow-500': toast.type === 'warning',
                        }"
                    >
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- Toaster dengan Action -->
    <template x-if="showActionToast">
        <div
            x-ref="toastActionElement"
            x-init="initActionToast($refs.toastActionElement)"
            class="pointer-events-auto w-full overflow-hidden rounded-lg shadow-lg bg-zinc-900 border border-zinc-900"
        >
            <div class="p-4">
                <div class="flex items-center justify-center">
                    <div class="flex-1">
                        <h3 x-text="actionToast.message" class="text-sm font-medium text-zinc-100"></h3>
                        <p x-text="actionToast.label" class="text-xs text-zinc-400 mt-1"></p>
                    </div>
                    <div class="flex space-x-3">
                        <flux:button
                            @click.stop="cancelAction()"
                        >
                            {{  __('Cancel') }}
                        </flux:button>
                        <flux:button
                            @click.stop="confirmAction()"
                            variant="primary"
                        >
                            {{ __('Save') }}
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
@endpersist
