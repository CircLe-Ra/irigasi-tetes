<?php

use App\Models\Device;
use App\Models\RelayChannel;
use Livewire\Attributes\{Computed, Layout, Title};
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app')]
#[Title('Pengontrolan')]
class extends Component {
    public $id;
    public $relays = [];
    public $channel;
    public $description_relay;
    public $deviceSelected;

    public $device_id;
    public $name;
    public $description;
    public $channels;

    #[Computed]
    public function devices()
    {
        return Device::with('channels')->get();
    }

    public function edit($id)
    {
        $device = Device::find($id);
        $this->device_id = $id;
        $this->name = $device->name;
        $this->description = $device->description;
        $this->channels = $device->channels->count();
        Flux::modal('device-modal')->show();
    }

    public function updateDevice()
    {
        $this->validate([
            'device_id' => 'required',
            'name' => 'required',
            'channels' => 'required',
            'description' => 'required',
        ]);

        try {
            $device = Device::find($this->device_id);
            $device->name = $this->name;
            $device->description = $this->description;
            $device->save();

            $device->channels()->delete();

            for ($i = 1; $i <= $this->channels; $i++) {
                RelayChannel::create([
                    'device_id' => $device->id,
                    'channel' => $i,
                    'state' => 0
                ]);
            }

            $this->__reset();
            Flux::modal('device-modal')->close();
            $this->dispatch('toast', message: 'Data berhasil disimpan');
        }catch (\Exception $e) {
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
            Device::find($this->id)->delete();
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
        $this->reset(['id', 'device_id', 'relays', 'name', 'description', 'channels', 'deviceSelected']);
    }

    public function __resetChannels(): void
    {
        $this->reset(['channel', 'description_relay']);
    }

    public function openRelay(Device $device)
    {
        $this->deviceSelected = $device;
        $this->relays = $this->deviceSelected->channels;
        Flux::modal('relay-modal')->show();
    }

    public function openModalSetDescription($id)
    {
        $this->channel = RelayChannel::find($id);
        $this->description_relay = $this->channel->description;
        Flux::modal('set-description-modal')->show();
    }

    public function setDescription()
    {
        $this->validate([
            'description_relay' => 'required',
        ]);

        try {
            RelayChannel::find($this->channel->id)->update(['description' => $this->description_relay]);
            $this->dispatch('toast', message: 'Deskripsi berhasil ditetapkan');
            Flux::modal('set-description-modal')->close();
        } catch (Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Deskripsi gagal ditetapkan ' . $e->getMessage());
            Flux::modal('set-description-modal')->close();
        }
    }

    public function sendToRelay(Device $device, $channel, $state)
    {
        try {
            $relay = $device->channels()->where('channel', $channel)->firstOrFail();
            $relay->update(['state' => $state]);
            $this->relays = Device::find($device->id)->channels;
        } catch (Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Relay gagal diubah ' . $e->getMessage());
        }
    }


}; ?>

<div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        @if (!$this->devices->isEmpty())
            @foreach ($this->devices as $device)
                <div class="relative">
                    <div class="absolute -top-3 -right-3">
                        <flux:button variant="primary" color="yellow" size="xs">
                            <flux:icon.pencil-square class="text-white" variant="micro" wire:click="edit({{ $device->id }})"/>
                        </flux:button>
                        <flux:button variant="primary" color="red" size="xs">
                            <flux:icon.x-mark class="text-white" variant="micro" wire:click="delete({{ $device->id }})"/>
                        </flux:button>
                    </div>
                    <div wire:click="openRelay({{ $device->id }})"
                     class="shadow-sm p-3 border rounded-lg bg-black/5 dark:bg-white/10 dark:border-white/10 flex flex-col justify-between">
                    <div class="flex items-center mb-3">
                        <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                            <flux:icon.gpu/>
                        </div>
                        <div class="ml-3">
                            <h2 class="font-bold text-base ">{{ $device->name }}</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-300">Device ID: {{ $device->id }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-300">{{ $device->description ?? 'Tanpa deskripsi' }}</p>
                        </div>
                    </div>

                    {{-- Relay indicators --}}
                    <div class="flex justify-center space-x-3 mt-3">
                        @foreach ($device->channels as $relay)
                            <div class="relative flex flex-col items-center">
                                <div
                                    @class([
                                        'absolute size-3 inline-flex rounded-full animate-ping',
                                        $relay->state
                                            ? 'bg-green-500 shadow-[0_0_6px_2px_rgba(34,197,94,0.7)]'
                                            : 'bg-red-500 shadow-[0_0_6px_2px_rgba(239,68,68,0.7)]'
                                    ])>
                                </div>
                                <span
                                    @class([
                                        'relative inline-flex size-3 rounded-full',
                                        $relay->state
                                            ? 'bg-green-500 shadow-[0_0_6px_2px_rgba(34,197,94,0.7)]'
                                            : 'bg-red-500 shadow-[0_0_6px_2px_rgba(239,68,68,0.7)]'
                                    ])>
                                </span>
                                <span class="text-[10px] text-gray-600 mt-1 dark:text-gray-300">CH{{ $relay->channel }}</span>
                            </div>
                        @endforeach
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

        <form method="POST" wire:submit="updateDevice" class="flex flex-col gap-6 mt-6">
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
    <flux:modal name="relay-modal" class="w-[calc(100%-10px)] md:w-96" @close="__reset()">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg" class="mb-2">{{ $this->deviceSelected?->name }}</flux:heading>
                <flux:callout color="sky">
                    <flux:callout.heading icon="newspaper">Informasi</flux:callout.heading>
                    <flux:callout.text>
                        Klik salah satu channel untuk menyalakan / mematikan relay.
                    </flux:callout.text>
                </flux:callout>
            </div>

            <div class="relative grid grid-cols-2 gap-4">
                @foreach ($this->relays as $channel)
                    <div class="relative">
                        <div class="absolute -top-2 -right-2">
                            <flux:button color="amber" variant="primary" icon="pencil" size="xs" wire:click="openModalSetDescription({{ $channel->id }})"/>
                        </div>
                        <div class=" w-full cursor-pointer rounded-lg py-4 text-center shadow-md transition
                           {{ $channel->state ? 'bg-green-500 text-white' : 'bg-red-500 text-gray-800' }}"
                             wire:click="sendToRelay({{ $this->deviceSelected?->id }},{{ $channel->channel }}, {{ $channel->state ? 0 : 1 }})"
                        >

                            <div class="font-bold text-lg text-white">Channel {{ $channel->channel }}</div>
                            <div class="mt-2 text-sm text-white">
                                {{ $channel->description ?? 'Tidak ada deskripsi' }}
                            </div>
                            <div class="mt-3 font-semibold text-white">
                                {{ $channel->state ? 'ON' : 'OFF' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </flux:modal>
    <flux:modal name="set-description-modal" class="w-[calc(100%-10px)] md:w-96" @close="__resetChannels()">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg" class="mb-2">Beri Deskripsi Channel {{ $this->channel?->channel }}</flux:heading>
                <flux:input wire:model="description_relay" label="Deskripsi" required/>
            </div>
        </div>
        <div class="flex gap-2 mt-2">
            <flux:spacer/>
            <flux:modal.close>
                <flux:button variant="ghost">Batal</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="primary" wire:click="setDescription">Simpan</flux:button>
        </div>
    </flux:modal>
</div>
