@props(['dataMenu', 'activeLink'])

<h1 class="text-xl font-semibold">Daftar Layanan</h1>
<flux:navlist wire:ignore class="rounded-lg py-5">
    <flux:navlist.group expandable heading="Kategori">
        @if($dataMenu)
            @foreach($dataMenu as $key => $menu)
                <flux:navlist.item class="py-5" :href="route('services', ['slug' => Str::of($menu)->slug('-')])" wire:navigate :current="$activeLink == Str::of($menu)->slug('-')">{{ $menu }}</flux:navlist.item>
            @endforeach
        @else
            <flux:navlist.item href="#" class="py-5 text-base pointer-not-allowed opacity-50">Tidak ada layanan</flux:navlist.item>
        @endif
    </flux:navlist.group>
</flux:navlist>
