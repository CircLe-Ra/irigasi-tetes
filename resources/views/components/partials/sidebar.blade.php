@props(['active', 'action' => null, 'tabUrl' => null, 'menu' => null, 'back' => null, 'dataMenu' => null, 'breadcrumbs' => true, 'activeLink' => null])
<div class="flex max-md:flex-col items-start">
    <div class="w-full md:w-[250px] me-2">
        @switch($menu)
            @case('master-data')
                <x-partials.menu-sidebar.master-data />
                @break
            @case('setting')
                <x-partials.menu-sidebar.setting />
                @break
            @case('report')
                <x-partials.menu-sidebar.report />
                @break
            @default
        @endswitch
        @isset($tabUrl)
            <x-tab-url :data-url="$tabUrl"  />
        @endisset
    </div>
    <flux:separator vertical="true" class="hidden md:block mr-2" />
    <div class="flex-1 max-md:pt-6 self-stretch">
        @if($breadcrumbs)
        <x-partials.breadcrumbs :active="$active" :action="$action" :back="$back"/>
        @endif
        <div class="mt-1">
            {{ $slot }}
        </div>
    </div>
</div>
