<flux:navbar class="-mb-px max-lg:hidden ">
    @role('admin')
        <flux:navbar.item icon="layout-dashboard" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate>Dashboard</flux:navbar.item>
    @endrole
</flux:navbar>
