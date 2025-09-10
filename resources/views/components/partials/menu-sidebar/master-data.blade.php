<flux:navlist wire:ignore class="border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 py-4 rounded-lg">
    <flux:navlist.group expandable heading="Pengguna">
        <flux:navlist.item class="py-5 text-base" href="{{ route('developer.master-data.users') }}" :current="request()->routeIs('developer.master-data.users')" wire:navigate>Manajemen Akun</flux:navlist.item>
        <flux:navlist.item class="py-5 text-base" href="{{ route('developer.master-data.roles') }}" :current="request()->routeIs('developer.master-data.roles')" wire:navigate>Manajemen Peran</flux:navlist.item>
    </flux:navlist.group>
</flux:navlist>
