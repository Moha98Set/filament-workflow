<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-600">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        مدیریت دستگاه‌ها
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        ثبت و مدیریت دستگاه‌های جدید
                    </p>
                </div>
            </div>

            <div class="flex gap-3">
                @php
                    $data = $this->getViewData();
                @endphp

                <!-- تعداد کل -->
                <div class="text-center px-4 py-2 rounded-lg bg-blue-50 dark:bg-blue-900/20">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $data['total_devices'] }}
                    </div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">
                        کل دستگاه‌ها
                    </div>
                </div>

                <!-- امروز -->
                <div class="text-center px-4 py-2 rounded-lg bg-green-50 dark:bg-green-900/20">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                        {{ $data['today_devices'] }}
                    </div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">
                        امروز
                    </div>
                </div>

                <!-- دستگاه‌های کاربر (فقط برای اپراتور) -->
                @if($data['user_devices'] !== null)
                <div class="text-center px-4 py-2 rounded-lg bg-purple-50 dark:bg-purple-900/20">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                        {{ $data['user_devices'] }}
                    </div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">
                        دستگاه‌های من
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- دکمه‌های اکشن -->
        <div class="mt-6 flex gap-3">
            <!-- دکمه مشاهده همه -->
            <a href="{{ route('filament.admin.resources.new-devices.index') }}" 
               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span class="font-semibold text-gray-700 dark:text-gray-300">مشاهده همه دستگاه‌ها</span>
            </a>

            <!-- دکمه ثبت دستگاه جدید (فقط اگر دسترسی داشته باشد) -->
            @can('create_devices')
            <a href="{{ route('filament.admin.resources.new-devices.create') }}" 
               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg transition-all shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="font-bold">ثبت دستگاه جدید</span>
            </a>
            @endcan
        </div>
    </x-filament::section>
</x-filament-widgets::widget>