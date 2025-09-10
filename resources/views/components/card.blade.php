table.blade.php@props(['mainClass' => null, 'label' => null, 'subLabel' => null])
<div class="p-6 border border-zinc-200 dark:border-zinc-700 mt-2 rounded-lg bg-zinc-50 dark:bg-zinc-900 {{ $mainClass }}">
    @isset($label)
        <flux:heading size="xl" level="1">{{ $label }}</flux:heading>
    @endisset
    @isset($subLabel)
        <flux:subheading class="mb-4">{{ $subLabel }}</flux:subheading>
    @endisset
    {{ $slot }}
</div>
