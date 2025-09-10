@props(['trash' => true, 'nameData' => 'name'])
<flux:modal class="min-w-[22rem]" :$attributes>
    <div class="space-y-6">
        @if($trash)
            <div>
                <flux:heading size="lg">Masukan ke tempat sampah?</flux:heading>
                <flux:text class=" p-4">
                    <ol class="list-decimal list-outside">
                        <li>Data tidak dihapus secara permanen. Anda dapat mengembalikan data ini jika diperlukan melalui menu <b>Tempat Sampah</b>.</li>
                    </ol>
                </flux:text>
            </div>
        @else
            <div>
                <flux:heading size="lg">Hapus Data Selamanya?</flux:heading>
                <flux:text class=" p-4">
                    <ol class="list-decimal list-outside">
                        <li>Anda akan menghapus data secara permanen.</li>
                        <li><b class="text-red-500">Anda tidak dapat mengembalikan data yang sudah dihapus</b>.</li>
                    </ol>
                </flux:text>
            </div>
        @endif
        <form wire:submit="destroy">
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger">Hapus</flux:button>
            </div>
        </form>
    </div>
</flux:modal>
