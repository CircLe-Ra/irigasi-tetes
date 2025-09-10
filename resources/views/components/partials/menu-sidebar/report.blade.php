<flux:navlist wire:ignore class=" border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 p-4 rounded-lg">
<flux:navlist.group expandable heading="Laporan">
        <flux:navlist.item class="py-5 text-base" :href="route('report.program-active')" wire:navigate>Program Kegiatan</flux:navlist.item>
        <flux:navlist.item class="py-5 text-base" :href="route('report.member-active')" wire:navigate>Keaktifan Pemuda</flux:navlist.item>
    </flux:navlist.group>
</flux:navlist>
