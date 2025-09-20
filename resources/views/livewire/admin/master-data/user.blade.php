<?php

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\{Title, Url, Computed};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

new
#[Title('Master Data - Pengguna')]
class extends Component {
    use WithPagination;

    #[Url(history: true, keep: true)]
    public $show = 5;
    #[Url(history: true, keep: true)]
    public string $search = '';
    public bool $showConfirmModal = false;

    public ?int $id = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role;

    #[Computed]
    public function users()
    {
        return User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->paginate((int)$this->show, pageName: 'user-page');
    }

    public function resetBagAndField(): void
    {
        $this->resetValidation();
        $this->reset(['id', 'name', 'email', 'password', 'password_confirmation']);
    }

    public function store(): void
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($this->id ?? null)],
            'role' => ['required', 'string']
        ];

        if (!$this->id) {
            $rules['password'] = ['required', 'string', 'min:3', 'confirmed'];
        } else {
            $rules['password'] = ['nullable', 'string', 'min:3', 'confirmed'];
        }

        $this->validate($rules);

        try {
            $data = [
                'name' => $this->name,
                'email' => $this->email,
            ];

            if ($this->password) {
                $data['password'] = bcrypt($this->password);
            }

            if ($this->id) {
                $user = User::find($this->id);
                $user->update($data);
                if ($user->hasRole($user->getRoleNames()->toArray())) {
                    foreach ($user->getRoleNames() as $roleName) {
                        $user->removeRole($roleName);
                    }
                }
                $user->assignRole($this->role);
            } else {
                User::create($data)->assignRole($this->role);
            }


            unset($this->users);
            Flux::modal('user-modal')->close();
            $this->dispatch('toast', message: 'Data pengguna berhasil disimpan');
        } catch (Exception $e) {
            Flux::modal('user-modal')->close();
            $this->dispatch('toast', type: 'error', message: 'Data pengguna gagal disimpan');
        }
    }

    public function edit($id): void
    {
        $user = User::find($id);
        $this->id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->getRoleNames()->first();
        Flux::modal('user-modal')->show();
    }

    public function delete($id): void
    {
        $this->showConfirmModal = true;
        $this->id = $id;
    }

    public function destroy(): void
    {
        try {
            User::find($this->id)->delete();
            $this->resetBagAndField();
            unset($this->users);
            $this->dispatch('toast', message: 'Data pengguna berhasil dihapus');
        } catch (Exception $e) {
            $this->resetBagAndField();
            $this->dispatch('toast', type: 'error', message: 'Data pengguna gagal dihapus ' . $e->getMessage());
        }
        $this->showConfirmModal = false;
    }

}; ?>

<section class="mt-2">

    <flux:modal.trigger name="user-modal">
        <flux:button variant="primary" class="w-[100px]" size="sm" icon="plus">Tambah</flux:button>
    </flux:modal.trigger>

    <flux:modal name="user-modal" class="md:w-96" @close="resetBagAndField">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Data Pengguna</flux:heading>
                <flux:text class="mt-2">Tambah atau Ubah Data Pengguna disini.</flux:text>
            </div>
            <form wire:submit="store" class="space-y-4">
                <flux:input label="Nama Lengkap" wire:model="name" autocomplete="off"/>
                <flux:input label="Email" wire:model="email" type="email" autocomplete="off"/>
                <flux:input label="Sandi" wire:model="password" type="password" autocomplete="new-password"/>
                <flux:input label="Konfirmasi Sandi" wire:model="password_confirmation" type="password"
                            autocomplete="new-password"/>
                <flux:select wire:model="role" label="Peran Pengguna">
                    <flux:select.option value="" selected>Pilih?</flux:select.option>
                    <flux:select.option value="admin">Admin</flux:select.option>
                </flux:select>
                <div class="flex ">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary">Simpan</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
    <x-confirm wire:model.self="showConfirmModal" :trash="false"/>

    <x-table thead="#, Nama, Email, Peran Pengguna" searchable label="Data Pengguna"
             sub-label="Daftar pengguna yang telah didaftarkan">
        <x-slot name="filter">
            <x-filter wire:model.live="show"/>
            <flux:input wire:model.live="search" size="sm" placeholder="Cari" class="w-full max-w-[220px]"/>
        </x-slot>
        @if($this->users->count())
            @foreach($this->users as $user)
                <tr>
                    <td class="px-6 py-4">
                        {{ $loop->iteration }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $user->name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $user->email }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $user->getRoleNames()->first() == 'host' ? 'Pemilik' : ($user->getRoleNames()->first() == 'developer' ? 'Developer' : 'Petugas') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <flux:button variant="primary" size="xs" icon="pencil"
                                         wire:click="edit({{ $user->id }})"/>
                            <flux:button class="disabled:cursor-not-allowed" variant="danger" size="xs" icon="trash"
                                         disabled="{{ $user->id == 1 }}" wire:click="delete({{ $user->id }})"/>
                        </div>
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
    {{ $this->users->links('livewire.pagination') }}
</section>
