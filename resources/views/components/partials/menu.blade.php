<flux:navbar class="-mb-px max-lg:hidden ">
    @role('admin')
        <flux:navbar.item icon="layout-dashboard" :href="route('admin.soil')" :current="request()->routeIs('admin.soil')" wire:navigate>Dashboard</flux:navbar.item>
    @endrole
</flux:navbar>
