<?php

use App\Models\Device;
use App\Models\RelayChannel;
use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app')]
#[Title('Tambah Pengontrol')]
class extends Component {

    public $device_id;
    public $name;
    public $description;
    public $channels;

    public function store()
    {
        $this->validate([
            'device_id' => 'required',
            'name' => 'required',
            'channels' => 'required',
            'description' => 'required',
        ]);

        try {
            $device = Device::create([
                'id' => $this->device_id,
                'name' => $this->name,
                'description' => $this->description,
            ]);

            for ($i = 1; $i <= $this->channels; $i++) {
                RelayChannel::create([
                    'device_id' => $device->id,
                    'channel' => $i,
                    'state' => 0
                ]);
            }

            $this->__reset();
            $this->dispatch('toast', message: 'Data berhasil disimpan');
            $this->redirect(route('admin.controller'), navigate: true);
        }catch (\Exception $e) {
            $this->__reset();
            $this->dispatch('toast', message: $e->getMessage());
        }
    }

    private function __reset(): void
    {
        $this->resetValidation();
        $this->reset(['device_id', 'name', 'description', 'channels']);
    }

}; ?>

<div class="w-full p-3 border rounded-lg bg-black/5 dark:bg-white/10 dark:border-white/10">
    <h3 class="text-lg font-semibold">Tambah Pengontrol</h3>
    <p class="text-sm text-gray-500 dark:text-gray-400">Alat pengontrol digunakan untuk mengontrol air pada irigasi
        tetes.</p>

    <form method="POST" wire:submit="store" class="flex flex-col gap-6 mt-6">
        <flux:input
            wire:model="device_id"
            label="ID NodeMCU"
            type="number"
            required
            autofocus
            autocomplete="device_id"
        />


        <flux:input
            wire:model="name"
            label="Nama Kontroler"
            required
            autofocus
            autocomplete="name"
        />

        <div class="relative">
            <flux:input
                wire:model="channels"
                label="Relay"
                badge="Jumlah Channel"
                type="number"
                required
            />
        </div>

        <flux:textarea
            wire:model="description"
            label="Deskripsi"
            required
        />

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
        </div>
    </form>
</div>
