@props(['label', 'alert' => true])
<flux:field>
    @isset($label)
        <flux:label>{{ $label ?? 'label' }}</flux:label>
    @endisset
    <div wire:ignore >
        <div x-data x-cloak x-init="() => {
                const pond = FilePond.create($refs.inputFilepond);
                pond.setOptions({
                    allowMultiple: {{ $attributes->has('multiple') ? 'true' : 'false' }},
                    labelIdle: '{!! __("Tarik & Lepas file disini atau Cari") !!}',
                    server: {
                        process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                            @this.upload('{{ $attributes->whereStartsWith('wire:model')->first() }}', file, load, error, progress)
                        },
                        revert: (filename, load) => {
                            @this.removeUpload('{{ $attributes->whereStartsWith('wire:model')->first() }}', filename, load)
                        },
                        load: (source, load, error, progress, abort, headers) => {
                            const myRequest = new Request(source);
                            fetch(myRequest).then((res) => {
                                return res.blob();
                            }).then(load);
                        },
                        allowImagePreview: {{ $attributes->has('allowImagePreview') ? 'true' : 'false' }},
                        imagePreviewMaxHeight: {{ $attributes->has('imagePreviewMaxHeight') ? $attributes->get('imagePreviewMaxHeight') : '256' }},
                        allowFileTypeValidation: {{ $attributes->has('allowFileTypeValidation') ? 'true' : 'false' }},
                        acceptedFileTypes: {!! $attributes->get('acceptedFileTypes') ?? 'null' !!},
                        labelFileTypeNotAllowed: 'Tipe file tidak sesuai',
                        allowFileSizeValidation: {{ $attributes->has('allowFileSizeValidation') ? 'true' : 'false' }},
                        maxFileSize: {!! $attributes->has('maxFileSize') ? "'" . $attributes->get('maxFileSize') . "'" : 'null' !!},
                    }
                });
                this.addEventListener('pond-edit', (event) => {
                    if(event.detail.file) {
                        pond.setOptions({
                            files: [{
                                source: '{{ asset('storage') }}/' + event.detail.file,
                                options: {
                                    type: 'local',
                                },
                            }],
                        });
                    }
                });
                this.addEventListener('pond-reset', e => {
                    pond.removeFiles();
                });
            }">
            <input type="file" x-ref="inputFilepond">
        </div>
    </div>
    @if($alert)
        <flux:error name="{{ $attributes->get('wire:model') }}" />
    @endif
</flux:field>
@pushonce('top-scripts')
    <script>
        window.addEventListener('livewire:navigated', () => {
            FilePond.registerPlugin(FilePondPluginFileValidateType);
            FilePond.registerPlugin(FilePondPluginFileValidateSize);
            FilePond.registerPlugin(FilePondPluginImagePreview);
        }, { once: true });
    </script>
@endpushonce
