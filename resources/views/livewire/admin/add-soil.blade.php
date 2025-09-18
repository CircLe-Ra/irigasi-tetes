<?php

use App\Models\Device;
use App\Models\RelayChannel;
use App\Models\Soil;
use Livewire\Attributes\{Computed, Layout, Title};
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app')]
#[Title('Tambah Sensor')]
class extends Component {

    public $id;
    public $device_id;
    public $name;
    public $channel;
    public $threshold;

    #[Computed]
    public function devices()
    {
        $usedDevices = Soil::select('device_id')
            ->groupBy('device_id')
            ->havingRaw('COUNT(*) >= 4')
            ->pluck('device_id');
        return Device::whereNotIn('id', $usedDevices)->get();
    }

    public function store()
    {
        $this->validate([
            'id' => 'required|unique:soils,id',
            'device_id' => 'required',
            'name' => 'required',
            'channel' => 'required',
            'threshold' => 'required',
        ]);

        try {
            $soil = Soil::create([
                'id' => $this->id,
                'device_id' => $this->device_id,
                'name' => $this->name,
                'channel' => $this->channel,
                'threshold' => $this->threshold,
            ]);

            $this->__reset();
            $this->dispatch('toast', message: 'Data berhasil disimpan');
            $this->redirect(route('admin.soil'), navigate: true);
        } catch (\Exception $e) {
            dd($e->getMessage());
            $this->__reset();
            $this->dispatch('toast', message: $e->getMessage());
        }
    }

    private function __reset(): void
    {
        $this->resetValidation();
        $this->reset([
            'id',
            'device_id',
            'name',
            'channel',
            'threshold',
            ]);
    }

}; ?>

<div class="w-full p-3 border rounded-lg bg-black/5 dark:bg-white/10 dark:border-white/10">
    <h3 class="text-lg font-semibold">Tambah Sensor</h3>
    <p class="text-sm text-gray-500 dark:text-gray-400">Alat sensor digunakan untuk pembacaan kelembaban tanah.</p>

    <form method="POST" wire:submit="store" class="flex flex-col gap-6 mt-6">
        <flux:input
            wire:model="id"
            label="ID NodeMCU Soil"
            type="number"
            required
            autofocus
            autocomplete="id"
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
            <flux:button variant="primary" type="submit" class="w-full">{{ __('Save')}}</flux:button>
        </div>
    </form>
</div>
