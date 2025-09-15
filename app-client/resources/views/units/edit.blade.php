<x-app-layout>
    <x-global.header>
        {{ __('units.edit') }} - {{ $unit->name }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    <x-global.session-alerts />

                    <form method="POST" action="{{ route('units.update', $unit->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Mobile-first grid: stack on mobile, two columns on md+ -->
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <!-- Identity card -->
                                <div class="space-y-4 order-2 md:order-2">
                                    <div>
                                        <x-input-label for="name" :value="__('units.name')" />
                                        <x-text-input id="name" name="name" type="text"
                                            class="mt-1 block w-full" :value="old('name', $unit->name)" required autofocus />
                                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                    </div>

                                    <div>
                                        <x-buttons.toggle-switch name="active" :label="__('fields.active')" :value="old('active', $unit->active)" />
                                    </div>
                                </div>

                                <!-- Image Upload / Replace card -->
                                <div class="space-y-3 order-1 md:order-1">
                                    <x-input-label for="unit_image" :value="__('units.image')" />
                                    <div class="flex items-center gap-4">
                                        <img id="unit-image-preview" src="{{ $unit->image_path ? Storage::disk('s3')->url($unit->image_path) : '' }}" alt="" class="{{ $unit->image_path ? '' : 'hidden' }} h-16 w-16 rounded-full object-cover ring-1 ring-gray-300 dark:ring-gray-700" />
                                        <div class="flex-1 space-y-2">
                                            <input id="unit_image" type="file" accept="image/*" class="sr-only" />
                                            <label id="select-image-label" for="unit_image" class="inline-flex w-full sm:w-auto items-center justify-center gap-2 px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:bg-green-700 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 cursor-pointer {{ $unit->image_path ? 'hidden' : '' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V9.414a2 2 0 00-.586-1.414l-3.414-3.414A2 2 0 0012.586 4H4zM3 5a1 1 0 011-1h8.586a1 1 0 01.707.293l3.414 3.414A1 1 0 0117 8.414V15a1 1 0 01-1 1H4a1 1 0 01-1-1V5z" />
                                                    <path d="M10 7a3 3 0 100 6 3 3 0 000-6z" />
                                                </svg>
                                                <span>{{ __('actions.select_image') }}</span>
                                            </label>
                                            <button type="button" id="remove-image-btn" class="{{ $unit->image_path ? '' : 'hidden' }} inline-flex w-full sm:w-auto items-center justify-center gap-2 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0115.916 21.75H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0L7.5 4.5m-1.25 1.188a48.11 48.11 0 013.478-.397m0 0L9.75 3.75A2.25 2.25 0 0112 2.25c.966 0 1.804.621 2.12 1.5l.022.062m-4.892.938a48.11 48.11 0 013.478.397" />
                                                </svg>
                                                <span>{{ __('actions.remove_image') }}</span>
                                            </button>
                                            <p id="unit-image-help" class="text-xs text-gray-500 dark:text-gray-400">{{ __('units.image_help') }}</p>
                                            <p id="unit-image-error" class="text-sm text-red-600 dark:text-red-400 hidden"></p>
                                        </div>
                                    </div>
                                    <input type="hidden" name="image_name" id="image_name" value="{{ old('image_name', $unit->image_name) }}">
                                    <input type="hidden" name="image_path" id="image_path" value="{{ old('image_path', $unit->image_path) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Actions: full-width on mobile, inline on desktop -->
                        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">
                            <x-cancel-link href="{{ route('units.index') }}" class="text-center">
                                {{ __('units.back') }}
                            </x-cancel-link>
                            <x-primary-button type="submit" class="w-full sm:w-auto">
                                {{ __('actions.save') }}
                            </x-primary-button>
                        </div>
                    </form>
                    <script>
                        (function() {
                            const fileInput = document.getElementById('unit_image');
                            const previewImg = document.getElementById('unit-image-preview');
                            const removeBtn = document.getElementById('remove-image-btn');
                            const imageNameInput = document.getElementById('image_name');
                            const imagePathInput = document.getElementById('image_path');
                            const errorEl = document.getElementById('unit-image-error');
                            const selectLabel = document.getElementById('select-image-label');
                            const helpEl = document.getElementById('unit-image-help');

                            function togglePreview(show) {
                                previewImg.classList.toggle('hidden', !show);
                                removeBtn.classList.toggle('hidden', !show);
                                selectLabel.classList.toggle('hidden', show);
                                if (helpEl) helpEl.classList.toggle('hidden', show);
                            }

                            async function deleteCurrentIfAny() {
                                const path = imagePathInput.value;
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
                                formData.append('directory', 'units');
                                // Extra client-side guardrails for clearer messages
                                const maxSize = 2 * 1024 * 1024; // 2MB
                                const allowed = ['image/jpeg','image/png','image/jpg','image/gif','image/svg+xml'];
                                if (!allowed.includes(file.type)) {
                                    errorEl.textContent = '{{ __('validation.image', ['attribute' => 'image']) }}';
                                    errorEl.classList.remove('hidden');
                                    return;
                                }
                                if (file.size > maxSize) {
                                    errorEl.textContent = '{{ __('validation.max.file', ['attribute' => 'image', 'max' => 2048]) }}';
                                    errorEl.classList.remove('hidden');
                                    return;
                                }

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
                                    return;
                                }
                                errorEl.textContent = '';
                                errorEl.classList.add('hidden');
                                imageNameInput.value = data.image_name;
                                imagePathInput.value = data.image_path;

                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    previewImg.src = e.target.result;
                                    togglePreview(true);
                                };
                                reader.readAsDataURL(file);
                            });

                            removeBtn.addEventListener('click', async function () {
                                await deleteCurrentIfAny();
                                imageNameInput.value = '';
                                imagePathInput.value = '';
                                fileInput.value = '';
                                previewImg.src = '';
                                togglePreview(false);
                            });

                            // Initialize state on page load
                            if (imagePathInput.value) {
                                togglePreview(true);
                            } else {
                                togglePreview(false);
                            }
                        })();
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
