<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Criar Novo Usuário') }}
            </h2>
            <a href="{{ route('admin.users.index') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Voltar') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <x-global.session-alerts />
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any())
                        <div
                            class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium">
                                        {{ __('Erro ao criar usuário') }}
                                    </h3>
                                    <div class="mt-2 text-sm">
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                        @csrf

                        <!-- Basic Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                {{ __('Informações Básicas') }}
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <label for="name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('Nome') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                        placeholder="{{ __('Digite o nome completo') }}" required>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('Email') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                        placeholder="{{ __('Digite o email') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Security -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                {{ __('Segurança') }}
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Password -->
                                <div>
                                    <label for="password"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('Senha') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" id="password" name="password"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                        placeholder="{{ __('Digite a senha') }}" required>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Mínimo 8 caracteres') }}
                                    </p>
                                </div>

                                <!-- Password Confirmation -->
                                <div>
                                    <label for="password_confirmation"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('Confirmar Senha') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                        placeholder="{{ __('Confirme a senha') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- User Role -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                {{ __('Função do Usuário') }}
                            </h3>

                            <div>
                                <label for="user_role_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Função') }} <span class="text-red-500">*</span>
                                </label>
                                <select id="user_role_id" name="user_role_id"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                    required>
                                    <option value="">{{ __('Selecione uma função') }}</option>
                                    @foreach ($userRoles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('user_role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Company Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                {{ __('Empresa') }}
                            </h3>

                            <!-- Create New Company Checkbox -->
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" id="create_new_company" name="create_new_company"
                                        value="1" {{ old('create_new_company') ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        {{ __('Criar nova empresa') }}
                                    </span>
                                </label>
                            </div>

                            <!-- New Company Fields (Hidden by default) -->
                            <div id="new_company_fields" class="hidden space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="company_name"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('Nome da Empresa') }} <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="company_name" name="company_name"
                                            value="{{ old('company_name') }}"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                            placeholder="{{ __('Digite o nome da empresa') }}">
                                    </div>

                                    <div>
                                        <label for="company_document_number"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('CNPJ/CPF') }}
                                        </label>
                                        <input type="text" id="company_document_number"
                                            name="company_document_number" value="{{ old('company_document_number') }}"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                            placeholder="{{ __('Digite o CNPJ ou CPF') }}">
                                    </div>
                                </div>

                                <div>
                                    <label for="company_document_type"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('Tipo de Documento') }}
                                    </label>
                                    <select id="company_document_type" name="company_document_type"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                        <option value="">{{ __('Selecione o tipo') }}</option>
                                        <option value="{{ \App\Enum\CompanyTypeEnum::CNPJ }}"
                                            {{ old('company_document_type') == \App\Enum\CompanyTypeEnum::CNPJ ? 'selected' : '' }}>
                                            {{ __('CNPJ') }}</option>
                                        <option value="{{ \App\Enum\CompanyTypeEnum::CPF }}"
                                            {{ old('company_document_type') == \App\Enum\CompanyTypeEnum::CPF ? 'selected' : '' }}>
                                            {{ __('CPF') }}</option>
                                    </select>
                                </div>

                                <!-- Plans -->
                                <div>
                                    <label for="plan_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('Plano') }}
                                    </label>
                                    <select id="plan_id" name="plan_id"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                        <option value="">{{ __('Selecione um plano') }}</option>
                                        @foreach ($plans as $plan)
                                            <option value="{{ $plan->id }}"
                                                {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                                {{ $plan->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <!-- Existing Company Selection (Visible by default) -->
                            <div id="existing_company_selection">
                                <label for="company_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Selecionar Empresa') }} <span class="text-red-500">*</span>
                                </label>
                                <select id="company_id" name="company_id"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                    <option value="">{{ __('Selecione uma empresa') }}</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Unit Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                {{ __('Unidade') }}
                            </h3>

                            <!-- Create New Unit Checkbox -->
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" id="create_new_unit" name="create_new_unit"
                                        value="1" {{ old('create_new_unit') ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        {{ __('Criar nova unidade') }}
                                    </span>
                                </label>
                            </div>

                            <!-- New Unit Fields (Hidden by default) -->
                            <div id="new_unit_fields" class="hidden">
                                <label for="unit_name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Nome da Unidade') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="unit_name" name="unit_name"
                                    value="{{ old('unit_name') }}"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                    placeholder="{{ __('Digite o nome da unidade') }}">
                            </div>

                            <!-- Existing Unit Selection (Visible by default) -->
                            <div id="existing_unit_selection">
                                <label for="unit_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Selecionar Unidade') }} <span class="text-red-500">*</span>
                                </label>
                                <select id="unit_id" name="unit_id"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                {{ __('Status') }}
                            </h3>

                            <div class="flex items-center">
                                <input type="checkbox" id="active" name="active" value="1"
                                    {{ old('active', true) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    {{ __('Usuário ativo') }}
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Usuários inativos não podem fazer login no sistema') }}
                            </p>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-6 flex justify-between">
                            <!-- Back Button -->
                            <x-cancel-link href="{{ route('admin.users.index') }}">
                                {{ __('Cancelar') }}
                            </x-cancel-link>

                            <!-- Create Button -->
                            <x-primary-button type="submit">
                                {{ __('actions.save') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Company checkbox functionality
            const createNewCompanyCheckbox = document.getElementById('create_new_company');
            const newCompanyFields = document.getElementById('new_company_fields');
            const existingCompanySelection = document.getElementById('existing_company_selection');
            const companyNameInput = document.getElementById('company_name');
            const companyIdSelect = document.getElementById('company_id');

            function toggleCompanyFields() {
                if (createNewCompanyCheckbox.checked) {
                    newCompanyFields.classList.remove('hidden');
                    existingCompanySelection.classList.add('hidden');
                    companyNameInput.required = true;
                    companyIdSelect.required = false;
                    companyIdSelect.value = '';
                    // Clear units when creating new company
                    clearUnitOptions();
                } else {
                    newCompanyFields.classList.add('hidden');
                    existingCompanySelection.classList.remove('hidden');
                    companyNameInput.required = false;
                    companyIdSelect.required = true;
                    companyNameInput.value = '';
                    // Load units for selected company if there's a value
                    if (companyIdSelect.value) {
                        loadUnitsByCompany(companyIdSelect.value);
                    }
                }
            }

            createNewCompanyCheckbox.addEventListener('change', toggleCompanyFields);
            toggleCompanyFields(); // Initial state

            // Unit checkbox functionality
            const createNewUnitCheckbox = document.getElementById('create_new_unit');
            const newUnitFields = document.getElementById('new_unit_fields');
            const existingUnitSelection = document.getElementById('existing_unit_selection');
            const unitNameInput = document.getElementById('unit_name');
            const unitIdSelect = document.getElementById('unit_id');

            function toggleUnitFields() {
                if (createNewUnitCheckbox.checked) {
                    newUnitFields.classList.remove('hidden');
                    existingUnitSelection.classList.add('hidden');
                    unitNameInput.required = true;
                    unitIdSelect.required = false;
                    unitIdSelect.value = '';
                } else {
                    newUnitFields.classList.add('hidden');
                    existingUnitSelection.classList.remove('hidden');
                    unitNameInput.required = false;
                    unitIdSelect.required = true;
                    unitNameInput.value = '';
                }
            }

            createNewUnitCheckbox.addEventListener('change', toggleUnitFields);
            toggleUnitFields(); // Initial state

            // Company selection change handler
            companyIdSelect.addEventListener('change', function() {
                if (this.value && !createNewCompanyCheckbox.checked) {
                    loadUnitsByCompany(this.value);
                } else {
                    clearUnitOptions();
                }
            });

            // Function to load units by company
            function loadUnitsByCompany(companyId) {
                // Show loading state
                unitIdSelect.disabled = true;
                unitIdSelect.innerHTML = '<option value="">Carregando unidades...</option>';

                fetch(`/admin/units/by-company/${companyId}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro na requisição');
                        }
                        return response.json();
                    })
                    .then(units => {
                        clearUnitOptions();

                        if (units.length === 0) {
                            const noUnitsOption = document.createElement('option');
                            noUnitsOption.value = '';
                            noUnitsOption.textContent = 'Nenhuma unidade encontrada para esta empresa';
                            unitIdSelect.appendChild(noUnitsOption);
                        } else {

                            // Add unit options
                            units.forEach(unit => {
                                const option = document.createElement('option');
                                option.value = unit.id;
                                option.textContent = unit.name;
                                unitIdSelect.appendChild(option);
                            });
                        }

                        unitIdSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Erro ao carregar unidades:', error);
                        clearUnitOptions();
                        const errorOption = document.createElement('option');
                        errorOption.value = '';
                        errorOption.textContent = 'Erro ao carregar unidades';
                        unitIdSelect.appendChild(errorOption);
                        unitIdSelect.disabled = false;
                    });
            }

            // Function to clear unit options
            function clearUnitOptions() {
                unitIdSelect.innerHTML = '';
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = '{{ __('Selecione uma unidade') }}';
                unitIdSelect.appendChild(defaultOption);
            }

            // Password strength validation
            document.getElementById('password').addEventListener('input', function() {
                const password = this.value;
                const minLength = 8;

                if (password.length < minLength) {
                    this.setCustomValidity(`A senha deve ter pelo menos ${minLength} caracteres`);
                } else {
                    this.setCustomValidity('');
                }
            });

            // Password confirmation validation
            document.getElementById('password_confirmation').addEventListener('input', function() {
                const password = document.getElementById('password').value;
                const confirmation = this.value;

                if (password !== confirmation) {
                    this.setCustomValidity('As senhas não coincidem');
                } else {
                    this.setCustomValidity('');
                }
            });
        </script>
    @endpush
</x-admin-layout>
