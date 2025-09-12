<?php

use App\Models\Device;
use Livewire\Attributes\{Computed, Layout, Title};
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app')]
#[Title('Pengontrolan')]
class extends Component {

    #[Computed]
    public function devices()
    {
        return Device::all();
    }

}; ?>

<div class="">
    @if($this->devices->count())
    <div class="grid grid-cols-2 gap-4">
        @foreach($this->devices as $device)
            <div class="relative">
                {{-- Tombol Hapus --}}
                <form class="absolute top-2 right-2 z-10">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </form>

                {{-- Card Device --}}
                <a href=""
                   class="bg-white shadow rounded-xl p-4 flex flex-col items-center justify-center hover:bg-blue-50 transition">
                    <div class="w-12 h-12 mb-2 flex items-center justify-center bg-blue-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 17v-6h6v6m2 4H7a2 2 0 01-2-2V7a2 2 0 012-2h4l2-2h4a2 2 0 012 2v14a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-base font-semibold">{{ $device->name }}</h2>
                    <p class="text-xs text-gray-500 text-center mt-1">
                        {{ $device->description ?? 'Tanpa deskripsi' }}
                    </p>
                </a>
            </div>
        @endforeach
    </div>
    @else
        <div class="text-center">
            <p class="text-gray-500">Tidak ada perangkat terdaftar</p>
        </div>
    @endif
</div>

