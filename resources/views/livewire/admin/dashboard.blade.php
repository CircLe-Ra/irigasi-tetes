<?php

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app')]
#[Title('Status Sensor')]
class extends Component {
    public $count;

    public function mount()
    {
        $this->count = 0;
    }

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;
    }

}; ?>

<div>
    <flux:button wire:click="increment">+</flux:button>
    {{ $count }}
    <flux:button wire:click="decrement">-</flux:button>
</div>
