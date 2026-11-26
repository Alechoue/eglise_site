@php
    $navigation = [
        ['label' => 'Accueil', 'href' => route('home')],
        ['label' => 'À propos', 'href' => url('/a-propos')],
        ['label' => 'Horaires et lieu', 'href' => url('/horaires-et-lieu')],
        ['label' => 'Événements', 'href' => url('/evenements')],
        ['label' => 'Prédications', 'href' => url('/predications')],
        ['label' => 'Contact', 'href' => url('/contact')],
    ];
@endphp

<header class="border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-4 sm:px-6 lg:px-8">
        <a
            href="{{ route('home') }}"
            class="rounded-sm text-lg font-semibold tracking-tight text-indigo-950 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-indigo-700"
            aria-label="{{ config('church.name') }} - Accueil"
        >
            {{ config('church.short_name') }}
        </a>

        <nav class="hidden items-center gap-6 md:flex" aria-label="Navigation principale">
            @foreach ($navigation as $item)
                <a
                    href="{{ $item['href'] }}"
                    @if (request()->fullUrlIs($item['href'])) aria-current="page" @endif
                    class="rounded-sm text-sm font-medium text-slate-700 transition hover:text-indigo-800 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-indigo-700"
                >
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <button
            type="button"
            class="inline-flex size-11 items-center justify-center rounded-md border border-slate-300 text-slate-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-700 md:hidden"
            aria-label="Ouvrir le menu"
            aria-expanded="false"
            aria-controls="mobile-navigation"
            data-menu-button
        >
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-5">
                <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <nav
        id="mobile-navigation"
        class="border-t border-slate-200 bg-white px-4 py-3 sm:px-6 md:hidden"
        aria-label="Navigation mobile"
        data-mobile-menu
        hidden
    >
        <ul class="mx-auto flex max-w-7xl flex-col gap-1">
            @foreach ($navigation as $item)
                <li>
                    <a
                        href="{{ $item['href'] }}"
                        class="block rounded-md px-3 py-3 text-sm font-medium text-slate-700 hover:bg-indigo-50 hover:text-indigo-900 focus-visible:outline-2 focus-visible:outline-indigo-700"
                    >
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
</header>
