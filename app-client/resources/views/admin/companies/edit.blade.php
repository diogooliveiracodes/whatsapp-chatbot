<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('companies.edit') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Alertas de Sessão -->
            <x-global.session-alerts />

            <!-- Formulário de Informações da Empresa -->
            <div class="bg-gray-800 shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-100 space-y-6">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-100">
                                {{ __('companies.basic_information') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-400">
                                {{ __('companies.basic_information_description') }}
                            </p>
                        </header>

                        <form action="{{ route('admin.companies.update', $company) }}" method="POST"
                            class="mt-6 space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <!-- Name Field -->
                                <div>
                                    <label for="name" class="label-style">{{ __('companies.name') }}</label>
                                    <input type="text" id="name" name="name"
                                        value="{{ old('name', $company->name) }}"
                                        class="input-style @error('name') border-red-500 @enderror" required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Document Type Field -->
                                <div>
                                    <label for="document_type"
                                        class="label-style">{{ __('companies.document_type') }}</label>
                                    <select id="document_type" name="document_type"
                                        class="input-style @error('document_type') border-red-500 @enderror">
                                        <option value="">{{ __('companies.select_type') }}</option>
                                        <option value="{{ \App\Enum\DocumentTypeEnum::CNPJ->value }}"
                                            {{ old('document_type', $company->document_type) == \App\Enum\DocumentTypeEnum::CNPJ->value ? 'selected' : '' }}>
                                            {{ __('companies.cnpj') }}
                                        </option>
                                        <option value="{{ \App\Enum\DocumentTypeEnum::CPF->value }}"
                                            {{ old('document_type', $company->document_type) == \App\Enum\DocumentTypeEnum::CPF->value ? 'selected' : '' }}>
                                            {{ __('companies.cpf') }}
                                        </option>
                                    </select>
                                    @error('document_type')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Document Number Field -->
                                <div>
                                    <label for="document_number"
                                        class="label-style">{{ __('companies.document_number') }}</label>
                                    <input required type="text" id="document_number" name="document_number"
                                        value="{{ old('document_number', $company->document_number) }}"
                                        class="input-style @error('document_number') border-red-500 @enderror"
                                        placeholder="{{ __('companies.document_placeholder') }}">
                                    @error('document_number')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Active Toggle -->
                                <x-buttons.toggle-switch name="active" :label="__('companies.active')" :value="old('active', $company->active)" />
                                @error('active')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-6 flex justify-between">
                                <x-cancel-link :href="route('admin.companies.index')">
                                    {{ __('companies.back') }}
                                </x-cancel-link>

                                <x-primary-button type="submit">
                                    {{ __('actions.save') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <!-- Formulário de Configurações da Empresa -->
            <div class="bg-gray-800 shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-100 space-y-6">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-100">
                                {{ __('company-settings.title') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-400">
                                {{ __('company-settings.description') }}
                            </p>
                        </header>

                        <form action="{{ route('admin.companies.settings.update', $company) }}" method="POST"
                            class="mt-6 space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- WhatsApp Configuration Section -->
                            <div class="space-y-6">
                                <h3 class="text-md font-medium text-gray-200">
                                    {{ __('company-settings.whatsapp_section') }}</h3>

                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="whatsapp_verify_token"
                                            class="label-style">{{ __('company-settings.whatsapp_verify_token') }}</label>
                                        <input type="text" id="whatsapp_verify_token" name="whatsapp_verify_token"
                                            value="{{ old('whatsapp_verify_token', $company->companySettings?->whatsapp_verify_token) }}"
                                            class="input-style @error('whatsapp_verify_token') border-red-500 @enderror">
                                        @error('whatsapp_verify_token')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="whatsapp_access_token"
                                            class="label-style">{{ __('company-settings.whatsapp_access_token') }}</label>
                                        <input type="text" id="whatsapp_access_token" name="whatsapp_access_token"
                                            value="{{ old('whatsapp_access_token', $company->companySettings?->whatsapp_access_token) }}"
                                            class="input-style @error('whatsapp_access_token') border-red-500 @enderror">
                                        @error('whatsapp_access_token')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="whatsapp_phone_number_id"
                                            class="label-style">{{ __('company-settings.whatsapp_phone_number_id') }}</label>
                                        <input type="text" id="whatsapp_phone_number_id" name="whatsapp_phone_number_id"
                                            value="{{ old('whatsapp_phone_number_id', $company->companySettings?->whatsapp_phone_number_id) }}"
                                            class="input-style @error('whatsapp_phone_number_id') border-red-500 @enderror">
                                        @error('whatsapp_phone_number_id')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="whatsapp_business_account_id"
                                            class="label-style">{{ __('company-settings.whatsapp_business_account_id') }}</label>
                                        <input type="text" id="whatsapp_business_account_id" name="whatsapp_business_account_id"
                                            value="{{ old('whatsapp_business_account_id', $company->companySettings?->whatsapp_business_account_id) }}"
                                            class="input-style @error('whatsapp_business_account_id') border-red-500 @enderror">
                                        @error('whatsapp_business_account_id')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- General Settings Section -->
                            {{-- <div class="space-y-6">
                                <h3 class="text-md font-medium text-gray-200">
                                    {{ __('company-settings.general_section') }}</h3>

                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="default_language"
                                            class="label-style">{{ __('company-settings.default_language') }}</label>
                                        <select id="default_language" name="default_language"
                                            class="input-style @error('default_language') border-red-500 @enderror">
                                            <option value="">{{ __('company-settings.select_language') }}
                                            </option>
                                            <option value="pt-BR"
                                                {{ old('default_language', $company->companySettings?->default_language) == 'pt-BR' ? 'selected' : '' }}>
                                                Português
                                            </option>
                                            <option value="en-US"
                                                {{ old('default_language', $company->companySettings?->default_language) == 'en-US' ? 'selected' : '' }}>
                                                English
                                            </option>
                                        </select>
                                        @error('default_language')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="timezone"
                                            class="label-style">{{ __('company-settings.timezone') }}</label>
                                        <select id="timezone" name="timezone"
                                            class="input-style @error('timezone') border-red-500 @enderror">
                                            <option value="">{{ __('company-settings.select_timezone') }}
                                            </option>
                                            <option value="America/Sao_Paulo"
                                                {{ old('timezone', $company->companySettings?->timezone) == 'America/Sao_Paulo' ? 'selected' : '' }}>
                                                America/Sao_Paulo (GMT-3)
                                            </option>
                                            <option value="America/New_York"
                                                {{ old('timezone', $company->companySettings?->timezone) == 'America/New_York' ? 'selected' : '' }}>
                                                America/New_York (GMT-5)
                                            </option>
                                            <option value="Europe/London"
                                                {{ old('timezone', $company->companySettings?->timezone) == 'Europe/London' ? 'selected' : '' }}>
                                                Europe/London (GMT+0)
                                            </option>
                                            <option value="UTC"
                                                {{ old('timezone', $company->companySettings?->timezone) == 'UTC' ? 'selected' : '' }}>
                                                UTC (GMT+0)
                                            </option>
                                        </select>
                                        @error('timezone')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <x-buttons.toggle-switch name="use_ai_chatbot" :label="__('company-settings.use_ai_chatbot')"
                                            :value="old(
                                                'use_ai_chatbot',
                                                $company->companySettings?->use_ai_chatbot,
                                            )" />
                                        @error('use_ai_chatbot')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div> --}}

                            <!-- Payment Gateway Section -->
                            <div class="space-y-6">
                                <h3 class="text-md font-medium text-gray-200">
                                    {{ __('company-settings.payment_gateway_section') }}</h3>

                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="payment_gateway"
                                            class="label-style">{{ __('company-settings.payment_gateway') }}</label>
                                        <select id="payment_gateway" name="payment_gateway"
                                            class="input-style @error('payment_gateway') border-red-500 @enderror">
                                            <option value="">{{ __('company-settings.select_gateway') }}
                                            </option>
                                            @foreach (\App\Enum\PaymentGatewayEnum::cases() as $gateway)
                                                <option value="{{ $gateway->value }}"
                                                    {{ old('payment_gateway', $company->companySettings?->payment_gateway?->value) == $gateway->value ? 'selected' : '' }}>
                                                    {{ $gateway->name() }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('payment_gateway')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="gateway_api_key"
                                            class="label-style">{{ __('company-settings.gateway_api_key') }}</label>
                                        <input type="text" id="gateway_api_key" name="gateway_api_key"
                                            value="{{ old('gateway_api_key', $company->companySettings?->gateway_api_key) }}"
                                            class="input-style @error('gateway_api_key') border-red-500 @enderror">
                                        @error('gateway_api_key')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- PIX Configuration Section -->
                            <div class="space-y-6">
                                <h3 class="text-md font-medium text-gray-200">{{ __('company-settings.pix_section') }}
                                </h3>

                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="pix_key"
                                            class="label-style">{{ __('company-settings.pix_key') }}</label>
                                        <input type="text" id="pix_key" name="pix_key"
                                            value="{{ old('pix_key', $company->companySettings?->pix_key) }}"
                                            class="input-style @error('pix_key') border-red-500 @enderror">
                                        @error('pix_key')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="pix_key_type"
                                            class="label-style">{{ __('company-settings.pix_key_type') }}</label>
                                        <select id="pix_key_type" name="pix_key_type"
                                            class="input-style @error('pix_key_type') border-red-500 @enderror">
                                            <option value="">{{ __('company-settings.select_pix_key_type') }}
                                            </option>
                                            @foreach (\App\Enum\PixKeyTypeEnum::cases() as $pixKeyType)
                                                <option value="{{ $pixKeyType->value }}"
                                                    {{ old('pix_key_type', $company->companySettings?->pix_key_type?->value) == $pixKeyType->value ? 'selected' : '' }}>
                                                    {{ $pixKeyType->label() }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('pix_key_type')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Bank Account Section -->
                            {{-- <div class="space-y-6">
                                <h3 class="text-md font-medium text-gray-200">
                                    {{ __('company-settings.bank_account_section') }}</h3>

                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="bank_code"
                                            class="label-style">{{ __('company-settings.bank_code') }}</label>
                                        <input type="text" id="bank_code" name="bank_code"
                                            value="{{ old('bank_code', $company->companySettings?->bank_code) }}"
                                            class="input-style @error('bank_code') border-red-500 @enderror"
                                            maxlength="10">
                                        @error('bank_code')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="bank_agency"
                                            class="label-style">{{ __('company-settings.bank_agency') }}</label>
                                        <input type="text" id="bank_agency" name="bank_agency"
                                            value="{{ old('bank_agency', $company->companySettings?->bank_agency) }}"
                                            class="input-style @error('bank_agency') border-red-500 @enderror"
                                            maxlength="20">
                                        @error('bank_agency')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="bank_account"
                                            class="label-style">{{ __('company-settings.bank_account') }}</label>
                                        <input type="text" id="bank_account" name="bank_account"
                                            value="{{ old('bank_account', $company->companySettings?->bank_account) }}"
                                            class="input-style @error('bank_account') border-red-500 @enderror"
                                            maxlength="20">
                                        @error('bank_account')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="bank_account_digit"
                                            class="label-style">{{ __('company-settings.bank_account_digit') }}</label>
                                        <input type="text" id="bank_account_digit" name="bank_account_digit"
                                            value="{{ old('bank_account_digit', $company->companySettings?->bank_account_digit) }}"
                                            class="input-style @error('bank_account_digit') border-red-500 @enderror"
                                            maxlength="5">
                                        @error('bank_account_digit')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="bank_account_type"
                                            class="label-style">{{ __('company-settings.bank_account_type') }}</label>
                                        <select id="bank_account_type" name="bank_account_type"
                                            class="input-style @error('bank_account_type') border-red-500 @enderror">
                                            <option value="">
                                                {{ __('company-settings.select_bank_account_type') }}</option>
                                            @foreach (\App\Enum\BankAccountTypeEnum::cases() as $bankAccountType)
                                                <option value="{{ $bankAccountType->value }}"
                                                    {{ old('bank_account_type', $company->companySettings?->bank_account_type?->value) == $bankAccountType->value ? 'selected' : '' }}>
                                                    {{ $bankAccountType->label() }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('bank_account_type')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="account_holder_name"
                                            class="label-style">{{ __('company-settings.account_holder_name') }}</label>
                                        <input type="text" id="account_holder_name" name="account_holder_name"
                                            value="{{ old('account_holder_name', $company->companySettings?->account_holder_name) }}"
                                            class="input-style @error('account_holder_name') border-red-500 @enderror"
                                            maxlength="255">
                                        @error('account_holder_name')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="account_holder_document"
                                            class="label-style">{{ __('company-settings.account_holder_document') }}</label>
                                        <input type="text" id="account_holder_document"
                                            name="account_holder_document"
                                            value="{{ old('account_holder_document', $company->companySettings?->account_holder_document) }}"
                                            class="input-style @error('account_holder_document') border-red-500 @enderror"
                                            maxlength="20">
                                        @error('account_holder_document')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div> --}}

                            <!-- Status Active -->
                            {{-- <div class="space-y-6">
                                <div class="md:col-span-2">
                                    <x-buttons.toggle-switch name="settings_active" :label="__('company-settings.active')"
                                        :value="old('settings_active', $company->companySettings?->active)" />
                                    @error('settings_active')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div> --}}

                            <!-- Action Buttons -->
                            <div class="mt-6 flex justify-between">
                                <x-cancel-link :href="route('admin.companies.index')">
                                    {{ __('companies.back') }}
                                </x-cancel-link>

                                <x-primary-button type="submit">
                                    {{ __('actions.save') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
