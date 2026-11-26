<footer class="border-t border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-8 text-sm text-slate-600 sm:px-6 md:flex-row md:items-end md:justify-between lg:px-8">
        <div class="flex flex-col gap-1">
            <p class="font-semibold text-slate-900">{{ config('church.name') }}</p>
            <p>{{ config('church.address') }}, {{ config('church.city') }}</p>
        </div>

        <div class="flex flex-col gap-1 md:items-end">
            <a class="w-fit rounded-sm hover:text-indigo-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-700" href="mailto:{{ config('church.email') }}">
                {{ config('church.email') }}
            </a>
            <a class="w-fit rounded-sm hover:text-indigo-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-700" href="tel:{{ preg_replace('/[^0-9+]/', '', config('church.phone')) }}">
                {{ config('church.phone') }}
            </a>
            <p class="mt-2">© {{ now()->year }} {{ config('church.short_name') }}</p>
        </div>
    </div>
</footer>
