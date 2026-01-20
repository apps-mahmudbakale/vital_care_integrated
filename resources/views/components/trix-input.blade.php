@props(['id', 'name', 'value' => ''])

<div
    x-data="{ 
        value: $wire.entangle('{{ $attributes->wire('model')->value() }}'),
        init() {
            this.$refs.editor.editor.loadHTML(this.value);
            this.$watch('value', (newValue) => {
                if (newValue !== this.$refs.editor.editor.getHTML()) {
                    this.$refs.editor.editor.loadHTML(newValue);
                }
            });
        },
        uploadAttachment(attachment) {
            const file = attachment.file;
            const form = new FormData();
            form.append('file', file);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route('app.trix.attachments') }}', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

            xhr.upload.onprogress = (event) => {
                const progress = (event.loaded / event.total) * 100;
                attachment.setUploadProgress(progress);
            };

            xhr.onload = () => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    const data = JSON.parse(xhr.responseText);
                    attachment.setAttributes({
                        url: data.url,
                        href: data.url
                    });
                }
            };

            xhr.send(form);
        }
    }"
    x-on:trix-change="value = $event.target.value"
    x-on:trix-attachment-add="uploadAttachment($event.attachment)"
    wire:ignore
    class="trix-container"
>
    <input
        type="hidden"
        name="{{ $name }}"
        id="{{ $id }}_input"
        :value="value"
    />

    <trix-toolbar
        class="[&_.trix-button]:bg-white [&_.trix-button.trix-active]:bg-gray-300"
        id="{{ $id }}_toolbar"
    ></trix-toolbar>

    <trix-editor
        id="{{ $id }}"
        x-ref="editor"
        toolbar="{{ $id }}_toolbar"
        input="{{ $id }}_input"
        {{ $attributes->whereDoesntStartWith('wire:model')->merge(['class' => 'trix-content border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring-1 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm dark:[&_pre]:!bg-gray-700 dark:[&_pre]:rounded dark:[&_pre]:!text-white']) }}
    ></trix-editor>
</div>
