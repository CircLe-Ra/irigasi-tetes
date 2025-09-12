<?php

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\{Title, Url, Computed};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

new
#[Title('Master Data - Peran')]
class extends Component {
    use WithPagination;

    #[Url(history: true, keep: true)]
    public $show = 5;
    #[Url(history: true, keep: true)]
    public string $search = '';

    public string $name = '';

    #[Computed]
    public function roles()
    {
        return Role::where('name', 'like', '%' . $this->search . '%')
            ->paginate((int)$this->show, pageName: 'role-page');
    }

}; ?>

<section class="mt-2">
    <x-partials.sidebar menu="master-data" active="Master Data / Pengguna / Manajemen Akun">

        <x-table thead="#, Nama Peran Pengguna, Jenis Proteksi" :action="false" label="Data Peran Pengguna"
                 sub-label="Daftar peran pengguna yang telah didaftarkan pada aplikasi">
            <x-slot name="filter">
                <x-filter wire:model.live="show"/>
                <flux:input wire:model.live="search" size="sm" placeholder="Cari" class="w-full max-w-[220px]"/>
            </x-slot>
            @if($this->roles->count())
                @foreach($this->roles as $role)
                    <tr>
                        <td class="px-6 py-4">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $role->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $role->guard_name }}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center">
                        Data tidak ditemukan
                    </td>
                </tr>
            @endif
        </x-table>
        {{ $this->roles->links('livewire.pagination') }}
    </x-partials.sidebar>
</section>
