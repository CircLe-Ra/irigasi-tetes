<?php

use App\Models\Device;
use App\Models\RelayChannel;
use App\Models\Soil;
use Livewire\Attributes\{Computed, Layout, Title};
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app')]
#[Title('Sensor Tanah')]
class extends Component {
    public $id;
    public $soil_id;
    public $device_id;
    public $name;
    public $channel;
    public $threshold;
    public $threshold_old;

    #[Computed]
    public function soils()
    {
        return Soil::all();
    }

    #[Computed]
    public function devices()
    {
        $usedDevices = Soil::select('device_id')
            ->groupBy('device_id')
            ->havingRaw('COUNT(*) >= 4')
            ->pluck('device_id');
        return Device::whereNotIn('id', $usedDevices)->get();
    }

    public function edit($id)
    {
        $soil = Soil::find($id);
        $this->id = $soil->id;
        $this->soil_id = $soil->id;
        $this->device_id = $soil->device_id;
        $this->name = $soil->name;
        $this->channel = (int)$soil->channel;
        $this->threshold = $soil->threshold;
        $this->threshold_old = $soil->threshold;
        Flux::modal('device-modal')->show();
    }

    public function updateSoil()
    {
        $this->validate([
            'soil_id' => 'required',
            'device_id' => 'required',
            'name' => 'required',
            'channel' => 'required',
            'threshold' => 'required',
        ]);

        try {
            $soil = Soil::find($this->id);
            $soil->id = $this->soil_id;
            $soil->device_id = $this->device_id;
            $soil->name = $this->name;
            $soil->channel = $this->channel;
            if($this->threshold != $this->threshold_old)
            {
                $soil->threshold = $this->threshold;
                $soil->reset = true;
            }
            $soil->save();

            $this->__reset();
            Flux::modal('device-modal')->close();
            $this->dispatch('toast', message: 'Data berhasil disimpan');
        } catch (\Exception $e) {
            $this->__reset();
            Flux::modal('device-modal')->close();
            $this->dispatch('toast', message: $e->getMessage());
        }
    }

    public function delete($id)
    {
        $this->id = $id;
        Flux::modal('delete-device')->show();
    }

    public function confirmDelete()
    {
        try {
            Soil::find($this->id)->delete();
            $this->dispatch('toast', message: 'Data berhasil dihapus');
            Flux::modal('delete-device')->close();
            $this->__reset();
            unset($this->devices);
        } catch (Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Data gagal dihapus ' . $e->getMessage());
            Flux::modal('delete-device')->close();
            $this->__reset();
        }
    }

    public function __reset(): void
    {
        $this->reset(['id', 'device_id', 'name', 'channel', 'threshold', 'threshold_old']);
    }


}; ?>

<div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6" wire:poll>
        @if (!$this->soils->isEmpty())
            @foreach ($this->soils as $soil)
                <div class="relative">
                    <div class="absolute -top-3 -right-3">
                        <flux:button variant="primary" color="yellow" size="xs">
                            <flux:icon.pencil-square class="text-white" variant="micro" wire:click="edit({{ $soil->id }})"/>
                        </flux:button>
                        <flux:button variant="primary" color="red" size="xs">
                            <flux:icon.x-mark class="text-white" variant="micro" wire:click="delete({{ $soil->id }})"/>
                        </flux:button>
                    </div>
                    <div
                         class="shadow-sm p-3 border rounded-lg bg-black/5 dark:bg-white/10 dark:border-white/10 flex flex-col justify-between">
                        <div class="flex items-center mb-3">
                            <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                                <flux:icon.gpu/>
                            </div>
                            <div class="ml-3">
                                <h2 class="font-bold text-base ">{{ $soil->name }}</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-300">Soil ID: {{ $soil->id }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-300">Data Acuan: {{ $soil->threshold }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-300">Perangkat Koneksi: {{ $soil->device->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-300">Relay Channel: {{ $soil->channel }}</p>
                                </div>
                        </div>

                        {{-- Relay indicators --}}
                        <flux:separator/>
                        <div class="flex justify-center space-x-3 mt-3 text-2xl">
                            {{ $soil->soil_value ?? 0 }} ADC
                            <flux:separator vertical class="mx-5" />
                            {{ $soil->soil_percent ?? 0 }} %
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div
                class="flex items-center justify-center p-6 border rounded-lg bg-black/5 dark:bg-white/10 dark:border-white/10">
                <p class="text-center">Tidak ada perangkat terdaftar.</p>
            </div>
        @endif
    </div>
    <flux:modal name="device-modal" class="w-[calc(100%-10px)] md:w-96" @close="__reset()">
        <h3 class="text-lg font-semibold">Ubah Pengontrol</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Alat pengontrol digunakan untuk mengontrol air pada irigasi
            tetes.</p>

        <form method="POST" wire:submit="updateSoil" class="flex flex-col gap-6 mt-6">
            <flux:input
                wire:model="soil_id"
                label="ID NodeMCU Soil"
                type="number"
                required
                autofocus
                autocomplete="soil_id"
            />

            <flux:input
                wire:model="name"
                label="Nama Sensor"
                required
                autofocus
                autocomplete="name"
            />

            <flux:select label="Pilih Kontroller" wire:model="device_id">
                <flux:select.option value="">Pilih Kontroller</flux:select.option>
                @foreach($this->devices as $device)
                    <flux:select.option value="{{ $device->id }}">
                        {{ $device->name }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <div class="relative">
                <flux:input
                    wire:model="channel"
                    label="Relay Channel"
                    badge="Channel 1 - 4"
                    type="number"
                    required
                />
            </div>

            <flux:input
                wire:model="threshold"
                label="Nilai Acuan (ADC)"
                type="number"
                required
                autofocus
                autocomplete="threshold"
            />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
            </div>
        </form>
    </flux:modal>
    <flux:modal name="delete-device" class="w-[calc(100%-10px)] md:w-96" @close="__reset()">
        <div class="space-y-6">
            <div>
                <flux:heading size="xl">Hapus Perangkat?</flux:heading>
                <flux:text class="mt-2">
                    <p class="text-sm">Kamu yakin ingin menghapus perangkat ini?</p>
                    <p class="text-sm">Perangkat ini akan dihapus secara permanen.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer/>
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" wire:click="confirmDelete">Hapus Perangkat</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
