<x-layouts.app title="Accueil">
    <section class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
        <p class="text-sm font-semibold uppercase tracking-widest text-indigo-800">
            {{ config('church.short_name') }}
        </p>
        <h1 class="max-w-3xl text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
            Bienvenue chez nous
        </h1>
        <p class="max-w-2xl text-lg leading-relaxed text-slate-600 sm:text-xl">
            {{ config('church.tagline') }}
        </p>
    </section>
</x-layouts.app>
