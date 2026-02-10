<div class="space-y-4" x-data="serialInputComponent()">
    {{-- Header با آمار --}}
    <div class="bg-primary-50 dark:bg-primary-900/20 rounded-lg p-4 border border-primary-200 dark:border-primary-700">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-primary-100 dark:bg-primary-800 rounded-lg">
                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">ثبت دسته‌جمعی دستگاه</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">هر سریال را در یک خط جدید وارد کنید</p>
                </div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-primary-600 dark:text-primary-400" x-text="count"></div>
                <div class="text-xs text-gray-500 dark:text-gray-400">سریال</div>
            </div>
        </div>
    </div>

    {{-- Textarea --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            سریال دستگاه‌ها <span class="text-red-500">*</span>
        </label>
        <textarea
            x-ref="textarea"
            x-model="serialText"
            @input="updateCount()"
            wire:model="serial_numbers"
            placeholder="مثال:&#10;&#10;FCCC&#10;FGGG&#10;FHHH&#10;FKKK&#10;FLLL"
            class="block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm focus:border-primary-500 dark:focus:border-primary-600 focus:ring-1 focus:ring-primary-500 dark:focus:ring-primary-600 font-mono text-base transition"
            style="min-height: 500px; resize: vertical;"
            rows="25"
            required
        ></textarea>
    </div>

    {{-- راهنما --}}
    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 space-y-2">
        <div class="flex items-start gap-2">
            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <strong>راهنمای استفاده:</strong>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>هر سریال را در یک خط جداگانه وارد کنید</li>
                    <li>برای رفتن به خط بعدی، کلید <kbd class="px-2 py-0.5 bg-gray-200 dark:bg-gray-700 rounded text-xs">Enter</kbd> را فشار دهید</li>
                    <li>سریال‌های تکراری به صورت خودکار نادیده گرفته می‌شوند</li>
                    <li>خطوط خالی حذف می‌شوند</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- پیش‌نمایش --}}
    <div x-show="count > 0" x-transition class="bg-success-50 dark:bg-success-900/20 rounded-lg p-4 border border-success-200 dark:border-success-700">
        <div class="flex items-center gap-2 mb-2">
            <svg class="w-5 h-5 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium text-success-700 dark:text-success-300">
                آماده ثبت <span x-text="count"></span> دستگاه
            </span>
        </div>
        <div class="text-xs text-success-600 dark:text-success-400">
            پس از انتخاب نوع دستگاه، دکمه "Create" را بزنید
        </div>
    </div>
</div>

@push('scripts')
<script>
    function serialInputComponent() {
        return {
            serialText: '',
            count: 0,
            
            init() {
                this.updateCount();
            },
            
            updateCount() {
                if (!this.serialText) {
                    this.count = 0;
                    return;
                }
                
                const lines = this.serialText
                    .split('\n')
                    .map(line => line.trim())
                    .filter(line => line.length > 0);
                
                this.count = lines.length;
            }
        }
    }
</script>
@endpush