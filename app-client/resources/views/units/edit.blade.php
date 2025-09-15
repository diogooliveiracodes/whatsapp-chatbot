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
                                <!-- Image Upload / Replace card (component) -->
                                <div class="space-y-3 order-1 md:order-1">
                                    <x-global.image-upload directory="units"
                                                          :initialImageName="old('image_name', $unit->image_name)"
                                                          :initialImagePath="old('image_path', $unit->image_path)"
                                                          nameImageName="image_name"
                                                          nameImagePath="image_path" />
                                </div>

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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
