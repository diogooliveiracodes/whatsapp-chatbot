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

                                <!-- Image Upload / Replace card (component) -->
                                <div class="space-y-3 order-1 md:order-1">
                                    <x-global.image-upload directory="units"
                                                          :initialImageName="old('image_name', $unit->image_name)"
                                                          :initialImagePath="old('image_path', $unit->image_path)"
                                                          nameImageName="image_name"
                                                          nameImagePath="image_path" />
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
