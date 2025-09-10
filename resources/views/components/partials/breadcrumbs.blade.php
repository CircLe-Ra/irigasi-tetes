@props(['active' => null, 'action' => null, 'back' => null, 'dashboard' => true])
@php
    if (isset($active)){
        $checkActive = explode('/', $active);
        $moreThanOne = count($checkActive) > 1;
    }
@endphp
<div class="flex justify-between items-center px-6 {{ $action ? 'py-3' : 'py-[18px]' }} rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900">
    <flux:breadcrumbs >
        @if($dashboard)
        <flux:breadcrumbs.item href="{{ route('goto') }}" separator="slash" wire:navigate>Dashboard</flux:breadcrumbs.item>
        @endif
        @if(isset($active))
            @if($moreThanOne)
                @foreach($checkActive as $key => $item)
                    <flux:breadcrumbs.item  separator="slash" href="{{ $back ? $back : '#' }}" wire:navigate>{{ $item }}</flux:breadcrumbs.item>
                @endforeach
            @else
                <flux:breadcrumbs.item separator="slash">{{ $active }}</flux:breadcrumbs.item>
            @endif
        @endif
    </flux:breadcrumbs>
    @isset($action)
        <flux:spacer />
        {!! $action !!}
    @endisset
</div>
