<flux:navlist wire:ignore class=" border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 p-4 rounded-lg">
    <flux:navlist.group expandable heading="Pengaturan">
        <flux:navlist.item class="py-5 text-base" :href="route('settings.profile')" wire:navigate>{{ __('Profile') }}</flux:navlist.item>
        <flux:navlist.item class="py-5 text-base" :href="route('settings.password')" wire:navigate>{{ __('Password') }}</flux:navlist.item>
        <flux:navlist.item class="py-5 text-base" :href="route('settings.appearance')" wire:navigate>{{ __('Appearance') }}</flux:navlist.item>
    </flux:navlist.group>
</flux:navlist>
