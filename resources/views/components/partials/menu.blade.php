<flux:navbar class="-mb-px max-lg:hidden ">
    @role('developer')
        <flux:navbar.item icon="layout-dashboard" :href="route('developer.dashboard')" :current="request()->routeIs('developer.dashboard')" wire:navigate>Dashboard</flux:navbar.item>
        <flux:navbar.item icon="database" :href="route('developer.master-data.users')" :current="request()->routeIs('developer.master-data*')" wire:navigate>Master Data</flux:navbar.item>
    @endrole
    @role('host')
        <flux:navbar.item icon="layout-dashboard" :href="route('host.dashboard')" :current="request()->routeIs('host.dashboard')" wire:navigate>Dashboard</flux:navbar.item>
    @endrole
</flux:navbar>
