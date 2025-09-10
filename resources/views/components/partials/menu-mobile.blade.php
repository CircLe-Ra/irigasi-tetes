<flux:navlist variant="outline">
    <flux:navlist.group :heading="__('Menu')">
            <flux:navlist.item icon="layout-dashboard" :href="route('host.dashboard')" :current="request()->routeIs('host.dashboard')" wire:navigate>
            Dashboard
            </flux:navlist.item>

    </flux:navlist.group>
</flux:navlist>
