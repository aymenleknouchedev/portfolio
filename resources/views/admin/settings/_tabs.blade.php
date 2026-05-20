@php
    $tabs = [
        ['key' => 'hero', 'label' => 'Hero', 'icon' => 'fa-image', 'route' => 'admin.settings.hero'],
        ['key' => 'general', 'label' => 'General', 'icon' => 'fa-globe', 'route' => 'admin.settings.general'],
        ['key' => 'social', 'label' => 'Social Media', 'icon' => 'fa-share-nodes', 'route' => 'admin.settings.social'],
        ['key' => 'about', 'label' => 'About', 'icon' => 'fa-circle-info', 'route' => 'admin.settings.about'],
        ['key' => 'account', 'label' => 'Account', 'icon' => 'fa-user-shield', 'route' => 'admin.settings.account'],
        ['key' => 'payment', 'label' => 'Payment', 'icon' => 'fa-credit-card', 'route' => 'admin.settings.payment'],
    ];
@endphp

<div class="rounded-2xl bg-gray-900 border border-white/5 p-1.5 mb-6 inline-flex flex-wrap gap-1 max-w-full">
    @foreach($tabs as $tab)
    @php $active = request()->routeIs($tab['route']); @endphp
    <a href="{{ route($tab['route']) }}"
        class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2.5 rounded-xl transition-all {{ $active ? ' text-white ' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
        <i class="fa-solid {{ $tab['icon'] }} text-xs"></i>
        {{ $tab['label'] }}
    </a>
    @endforeach
</div>
