@props(['dataUrl' => null])
@php
    $expUrl = Str::of($dataUrl)->explode(':');
@endphp
<flux:navlist wire:ignore class="mt-2 border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 p-4 rounded-lg">
    @if($expUrl->count() > 0)
        <flux:navlist.group expandable heading="Menu">
            @foreach($expUrl as $key => $data)
                @php
                    $expNameUrl = Str::of($data)->explode('|');
                @endphp
                @if($expNameUrl->count() <= 1)
                    <flux:tooltip toggleable position="bottom">
                        <flux:button icon:trailing="information-circle" size="sm" class="w-full" variant="danger">Tab Error</flux:button>
                        <flux:tooltip.content class="max-w-[30rem] space-y-2 text-left dark:bg-zinc-900">
                            <p>Informasi Untukmu!</p>
                            <p>Pastikan format yang dimasuk sudah sesuai</p>
                            <p>Contoh : Nama Tab|nama.route</p>
                            <p>Jika Lebih dari 1 tab maka dipisahkan dengan (:) tanpa kurung</p>
                            <p>Contoh : Nama Tab|nama.route:Nama Tab 2|nama.route.2:dst</p>
                        </flux:tooltip.content>
                    </flux:tooltip>
                @else
                    <flux:navlist.item class="py-5 text-base" href="{{ route($expNameUrl[1]) }}" wire:navigate>{{ $expNameUrl[0] }}</flux:navlist.item>
                @endif
            @endforeach
        </flux:navlist.group>
    @endif
</flux:navlist>
