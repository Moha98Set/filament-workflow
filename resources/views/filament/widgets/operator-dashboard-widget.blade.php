{{--
    آدرس فایل: resources/views/filament/widgets/operator-dashboard-widget.blade.php
--}}

<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-4">
            {{-- تایتل --}}
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-success-500/10">
                    <svg class="w-6 h-6 text-success-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    کارهای امروز
                </h3>
            </div>

            {{-- محتوای اصلی --}}
            <div class="rounded-lg border border-success-500/20 bg-success-50 dark:bg-success-500/10 p-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-success-600 dark:text-success-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-success-900 dark:text-success-100">
                            تعداد <span class="text-lg font-bold">{{ $newClientsCount }}</span> مشتری جدید ثبت‌نام کردند و منتظر تایید اطلاعات هستند.
                        </p>

                        @if($todayClientsCount > 0)
                            <p class="mt-2 text-xs text-success-700 dark:text-success-300">
                                امروز: {{ $todayClientsCount }} مشتری جدید
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- دکمه عملیات (اختیاری) --}}
            @if($newClientsCount > 0)
                <div class="flex justify-end">
                    <a href="{{ route('filament.admin.resources.clients.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-500 rounded-lg transition">
                        <span>مشاهده مشتریان</span>
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
