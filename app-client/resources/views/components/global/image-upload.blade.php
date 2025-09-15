@props([
    'label' => __('units.image'),
    'directory',
    'nameImageName' => 'image_name',
    'nameImagePath' => 'image_path',
    'initialImageName' => old('image_name'),
    'initialImagePath' => old('image_path'),
    'help' => __('units.image_help'),
    'selectText' => __('actions.select_image'),
    'removeText' => __('actions.remove_image'),
    'avatarSize' => 'h-16 w-16',
    'successColor' => 'green',
    'uid' => null,
])

@php
    $uid = $uid ?? uniqid('img_');
    // Use static classes instead of dynamic ones for better Tailwind CSS support
    $btnSelectClasses = 'inline-flex w-full sm:w-auto items-center justify-center gap-2 px-4 py-2 '
        . 'border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest '
        . 'transition ease-in-out duration-150 cursor-pointer '
        . 'bg-green-600 hover:bg-green-500 focus:bg-green-700 active:bg-green-700 '
        . 'focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2';
@endphp

<div class="space-y-3">
    <x-input-label :for="'file-' . $uid" :value="$label" />
    <div class="flex items-center gap-4">
        <img id="preview-{{ $uid }}" src="{{ $initialImagePath ? \Illuminate\Support\Facades\Storage::disk('s3')->url($initialImagePath) : '' }}" alt="" class="{{ $initialImagePath ? '' : 'hidden' }} {{ $avatarSize }} rounded-full object-cover ring-1 ring-gray-300 dark:ring-gray-700" />
        <div class="flex-1 space-y-2">
            <input id="file-{{ $uid }}" type="file" accept="image/*" class="sr-only" />
            <label id="select-{{ $uid }}" for="file-{{ $uid }}" class="{{ $btnSelectClasses }} {{ $initialImagePath ? 'hidden' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V9.414a2 2 0 00-.586-1.414l-3.414-3.414A2 2 0 0012.586 4H4zM3 5a1 1 0 011-1h8.586a1 1 0 01.707.293l3.414 3.414A1 1 0 0117 8.414V15a1 1 0 01-1 1H4a1 1 0 01-1-1V5z" />
                    <path d="M10 7a3 3 0 100 6 3 3 0 000-6z" />
                </svg>
                <span>{{ $selectText }}</span>
            </label>

            <button type="button" id="remove-{{ $uid }}" class="{{ $initialImagePath ? '' : 'hidden' }} inline-flex w-full sm:w-auto items-center justify-center gap-2 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0115.916 21.75H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0L7.5 4.5m-1.25 1.188a48.11 48.11 0 013.478-.397m0 0L9.75 3.75A2.25 2.25 0 0112 2.25c.966 0 1.804.621 2.12 1.5l.022.062m-4.892.938a48.11 48.11 0 013.478.397" />
                </svg>
                <span>{{ $removeText }}</span>
            </button>

            <span id="spinner-{{ $uid }}" class="hidden inline-flex items-center gap-2 text-gray-600 dark:text-gray-300">
                <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
            </span>

            <p id="help-{{ $uid }}" class="text-xs text-gray-500 dark:text-gray-400 {{ $initialImagePath ? 'hidden' : '' }}">{{ $help }}</p>
            <p id="error-{{ $uid }}" class="text-sm text-red-600 dark:text-red-400 hidden"></p>
        </div>
    </div>

    <input type="hidden" name="{{ $nameImageName }}" id="name-{{ $uid }}" value="{{ $initialImageName }}">
    <input type="hidden" name="{{ $nameImagePath }}" id="path-{{ $uid }}" value="{{ $initialImagePath }}">
</div>

<script>
    (function() {
        const fileInput = document.getElementById('file-{{ $uid }}');
        const previewImg = document.getElementById('preview-{{ $uid }}');
        const removeBtn = document.getElementById('remove-{{ $uid }}');
        const selectLabel = document.getElementById('select-{{ $uid }}');
        const helpEl = document.getElementById('help-{{ $uid }}');
        const errorEl = document.getElementById('error-{{ $uid }}');
        const nameInput = document.getElementById('name-{{ $uid }}');
        const pathInput = document.getElementById('path-{{ $uid }}');
        const spinnerEl = document.getElementById('spinner-{{ $uid }}');

        function togglePreview(show) {
            previewImg.classList.toggle('hidden', !show);
            removeBtn.classList.toggle('hidden', !show);
            selectLabel.classList.toggle('hidden', show);
            if (helpEl) helpEl.classList.toggle('hidden', show);
        }

        function setLoading(isLoading) {
            if (!spinnerEl) return;
            spinnerEl.classList.toggle('hidden', !isLoading);
            fileInput.disabled = isLoading;
            removeBtn.disabled = isLoading;
            selectLabel.classList.toggle('pointer-events-none', isLoading);
            selectLabel.classList.toggle('opacity-50', isLoading);
        }

        async function deleteCurrentIfAny() {
            const path = pathInput.value;
            if (!path) return;
            try {
                await fetch("{{ route('delete-image') }}", {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ image_path: path })
                });
            } catch (e) {}
        }

        fileInput.addEventListener('change', async function () {
            const file = this.files && this.files[0];
            if (!file) return;

            await deleteCurrentIfAny();

            const formData = new FormData();
            formData.append('image', file);
            formData.append('directory', '{{ $directory }}');

            // Client-side guardrails (20MB, types)
            const maxSize = 20 * 1024 * 1024;
            const allowed = ['image/jpeg','image/png','image/jpg','image/gif','image/svg+xml'];
            if (!allowed.includes(file.type)) {
                errorEl.textContent = '{{ __('validation.image', ['attribute' => 'image']) }}';
                errorEl.classList.remove('hidden');
                return;
            }
            if (file.size > maxSize) {
                errorEl.textContent = '{{ __('validation.max.file', ['attribute' => 'image', 'max' => 20480]) }}';
                errorEl.classList.remove('hidden');
                return;
            }

            setLoading(true);
            const res = await fetch("{{ route('upload-image') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData,
                credentials: 'same-origin'
            });

            let data; let msg = '';
            const ct = res.headers.get('content-type') || '';
            if (ct.includes('application/json')) {
                data = await res.json().catch(() => ({}));
            } else {
                const text = await res.text().catch(() => '');
                data = {};
                if (!res.ok) msg = text?.slice(0, 500);
            }
            if (!res.ok) {
                if (!msg) {
                    if (data?.errors) {
                        const allErrors = Object.values(data.errors).flat();
                        if (allErrors.length) msg = allErrors.join(' ');
                    }
                    if (!msg && data?.message) msg = data.message;
                }
                if (!msg) msg = '{{ __('units.error.image_upload') }}';
                errorEl.textContent = msg;
                errorEl.classList.remove('hidden');
                setLoading(false);
                return;
            }
            errorEl.textContent = '';
            errorEl.classList.add('hidden');
            nameInput.value = data.image_name;
            pathInput.value = data.image_path;
            setLoading(false);

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                togglePreview(true);
            };
            reader.readAsDataURL(file);
        });

        removeBtn.addEventListener('click', async function () {
            await deleteCurrentIfAny();
            nameInput.value = '';
            pathInput.value = '';
            fileInput.value = '';
            previewImg.src = '';
            togglePreview(false);
        });

        // Initialize
        togglePreview(!!pathInput.value);
    })();
</script>


