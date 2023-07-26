@props([
    'navigation',
])

@php
    $buttonClasses = \Illuminate\Support\Arr::toCssClasses([
        'shrink-0 -ms-1.5 me-4',
        'lg:me-4' => filament()->isSidebarFullyCollapsibleOnDesktop(),
        'lg:hidden' => ! filament()->isSidebarFullyCollapsibleOnDesktop(),
    ]);
@endphp

<nav
    {{ $attributes->class(['fi-topbar sticky top-0 z-20 flex h-16 items-center bg-white px-4 shadow-[0_1px_0_0_theme(colors.gray.950_/_5%)] transition dark:bg-gray-900 dark:shadow-[0_1px_0_0_theme(colors.white_/_10%)] md:px-6 lg:px-8']) }}
>
    {{ \Filament\Support\Facades\FilamentView::renderHook('topbar.start') }}

    <x-filament::icon-button
        color="gray"
        icon="heroicon-o-bars-3"
        icon-alias="panels::topbar.open-mobile-sidebar-button"
        icon-size="lg"
        :label="__('filament::layout.actions.sidebar.expand.label')"
        x-cloak
        x-data="{}"
        x-on:click="$store.sidebar.open()"
        x-show="! $store.sidebar.isOpen"
        :class="$buttonClasses"
    />

    <x-filament::icon-button
        color="gray"
        icon="heroicon-o-x-mark"
        icon-alias="panels::topbar.close-mobile-sidebar-button"
        icon-size="lg"
        :label="__('filament::layout.actions.sidebar.collapse.label')"
        x-cloak
        x-data="{}"
        x-on:click="$store.sidebar.close()"
        x-show="$store.sidebar.isOpen"
        :class="$buttonClasses"
    />

    @if (filament()->hasTopNavigation())
        <div class="me-6 hidden lg:flex">
            @if ($homeUrl = filament()->getHomeUrl())
                <a
                    href="{{ $homeUrl }}"
                    {{-- wire:navigate --}}
                >
                    <x-filament::logo />
                </a>
            @else
                <x-filament::logo />
            @endif
        </div>

        @if (filament()->hasNavigation())
            <ul class="me-4 hidden items-center gap-x-4 lg:flex">
                @foreach ($navigation as $group)
                    @if ($groupLabel = $group->getLabel())
                        <x-filament::dropdown placement="bottom-start">
                            <x-slot name="trigger">
                                <x-filament::topbar.item
                                    :active="$group->isActive()"
                                    :icon="$group->getIcon()"
                                >
                                    {{ $groupLabel }}
                                </x-filament::topbar.item>
                            </x-slot>

                            <x-filament::dropdown.list>
                                @foreach ($group->getItems() as $item)
                                    @php
                                        $icon = $item->getIcon();
                                        $shouldOpenUrlInNewTab = $item->shouldOpenUrlInNewTab();
                                    @endphp

                                    <x-filament::dropdown.list.item
                                        :href="$item->getUrl()"
                                        :icon="$item->isActive() ? ($item->getActiveIcon() ?? $icon) : $icon"
                                        tag="a"
                                        :target="$shouldOpenUrlInNewTab ? '_blank' : null"
                                        {{-- :wire:navigate="$shouldOpenUrlInNewTab ? null : true" --}}
                                    >
                                        {{ $item->getLabel() }}
                                    </x-filament::dropdown.list.item>
                                @endforeach
                            </x-filament::dropdown.list>
                        </x-filament::dropdown>
                    @else
                        @foreach ($group->getItems() as $item)
                            <x-filament::topbar.item
                                :active="$item->isActive()"
                                :active-icon="$item->getActiveIcon()"
                                :badge="$item->getBadge()"
                                :badge-color="$item->getBadgeColor()"
                                :icon="$item->getIcon()"
                                :should-open-url-in-new-tab="$item->shouldOpenUrlInNewTab()"
                                :url="$item->getUrl()"
                            >
                                {{ $item->getLabel() }}
                            </x-filament::topbar.item>
                        @endforeach
                    @endif
                @endforeach
            </ul>
        @endif
    @endif

    <div
        {{-- x-persist="topbar.end" --}}
        class="ms-auto flex items-center gap-x-4"
    >
        @if (filament()->isGlobalSearchEnabled())
            @livewire(Filament\Livewire\GlobalSearch::class, ['lazy' => true])
        @endif

        @if (filament()->hasDatabaseNotifications())
            @livewire(Filament\Livewire\DatabaseNotifications::class, ['lazy' => true])
        @endif

        <x-filament::user-menu />
    </div>

    {{ \Filament\Support\Facades\FilamentView::renderHook('topbar.end') }}
</nav>
