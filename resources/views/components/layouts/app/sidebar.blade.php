<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky collapsible="mobile" class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.header>
            <flux:sidebar.brand
                href="#"
                logo="https://fluxui.dev/img/demo/logo.png"
                logo:dark="https://fluxui.dev/img/demo/dark-mode-logo.png"
                name="Acme Inc."
            />
            <flux:sidebar.collapse />
        </flux:sidebar.header>
        <flux:sidebar.nav>
            <flux:sidebar.item icon="microchip" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate>Sensor Status</flux:sidebar.item>
            <flux:sidebar.item icon="cpu" :href="route('admin.controller')" :current="request()->routeIs('admin.controller')" wire:navigate>Pengontrolan</flux:sidebar.item>
        </flux:sidebar.nav>
        <flux:sidebar.spacer />
        <flux:sidebar.nav>
            <flux:sidebar.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:sidebar.item>
            <flux:sidebar.item icon="information-circle" href="#">Help</flux:sidebar.item>
        </flux:sidebar.nav>
        <flux:dropdown position="bottom" align="start">
            <flux:profile
                :name="auth()->user()->name"
                :initials="auth()->user()->initials()"
                icon-trailing="chevrons-up-down"
            />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>
    <flux:header class="block! bg-white lg:bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
        <flux:navbar class="lg:hidden w-full" >
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            {{ $title ?? config('app.name') }}
            <flux:spacer />
            <flux:dropdown align="end" >
                <flux:button icon:trailing="ellipsis-vertical" variant="ghost" inset="right"></flux:button>
                <flux:menu>
                    <flux:menu.item icon="rotate-cw" :href="route(Route::currentRouteName())" wire:navigate>Refresh</flux:menu.item>
                    @if(request()->routeIs('admin.controller'))
                    <flux:menu.separator />
                    <flux:menu.item icon="plus" :href="route('admin.controller.create')" wire:navigate>Tambah Perangkat</flux:menu.item>
                    @endif
                </flux:menu>
            </flux:dropdown>
        </flux:navbar>
        <flux:navbar scrollable @class([
            'flex justify-center gap-4 border-t border-zinc-200 dark:border-zinc-700',
            'hidden' => !request()->routeIs('admin.dashboard', 'admin.controller'),
        ])>
            <flux:navbar.item icon="microchip" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate>Status Sensor</flux:navbar.item>
            <flux:navbar.item icon="cpu" :href="route('admin.controller')" :current="request()->routeIs('admin.controller')" wire:navigate>Pengontrolan</flux:navbar.item>
        </flux:navbar>
    </flux:header>

    {{ $slot }}

        @fluxScripts
    </body>
</html>
