# Padrões de Projeto - Funcionalidade Unit

Este documento descreve os padrões de projeto e boas práticas adotados na implementação da funcionalidade Unit, servindo como referência para padronização de outras rotinas do sistema.

## Services

### Responsabilidades
- Encapsulamento da lógica de negócio
- Orquestração de operações complexas
- Interação com repositories
- Tratamento de exceções específicas do domínio

### Estrutura e Boas Práticas
```php
class UnitService
{
    public function __construct(protected UnitRepository $unitRepository) {}

    public function getUnits()
    {
        return $this->unitRepository->getUnits();
    }

    public function create(array $data)
    {
        return $this->unitRepository->create($data);
    }

    public function update(Unit $unit, array $data)
    {
        return $this->unitRepository->update($unit, $data);
    }

    public function delete(Unit $unit)
    {
        return $this->unitRepository->delete($unit);
    }
}
```

### Boas Práticas
1. Injeção de dependência via construtor
2. Métodos concisos e com responsabilidade única
3. Uso de type hints e return types
4. Encapsulamento da lógica de negócio
5. Delegação de operações de dados para o repository

## Repositories

### Abordagem para Isolamento de Dados
- Abstração da camada de acesso a dados
- Centralização de queries complexas
- Reutilização de código de acesso a dados

### Implementação
```php
class UnitRepository
{
    public function __construct(protected Unit $model) {}

    public function getUnits(): Collection
    {
        return $this->model->where('company_id', Auth::user()->company_id)->get();
    }

    public function create(array $data): Unit
    {
        $data['company_id'] = Auth::user()->company_id;
        return $this->model->create($data);
    }

    public function update(Unit $unit, array $data): Unit
    {
        if(!$data['active']) {
            $data['active'] = 0;
        }
        $unit->update($data);
        return $unit;
    }
}
```

### Boas Práticas
1. Injeção do modelo via construtor
2. Métodos com retorno tipado
3. Encapsulamento de lógica de queries
4. Tratamento de dados sensíveis (ex: company_id)
5. Validações de dados antes de operações

## Views

### Estrutura e Organização
```
resources/
└── views/
    └── units/
        ├── index.blade.php
        ├── create.blade.php
        ├── edit.blade.php
        └── show.blade.php
```

### Padrões de Componentização
1. Uso de componentes Blade reutilizáveis
2. Layouts consistentes com `<x-app-layout>`
3. Componentes de formulário padronizados
4. Mensagens de feedback padronizadas

### Estilização
- Uso consistente do Tailwind CSS
- Classes organizadas por funcionalidade
- Componentes de UI reutilizáveis
- Suporte a tema claro/escuro

Exemplo de estrutura:
```blade
<x-app-layout>
    <x-header>
        {{ __('units.create') }}
    </x-header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Conteúdo -->
            </div>
        </div>
    </div>
</x-app-layout>
```

## Configurações de Tradução

### Organização
```
resources/
└── lang/
    ├── en/
    │   └── units.php
    └── pt_BR/
        └── units.php
```

### Padrões de Chaves
```php
return [
    'edit' => 'Edit Unit',
    'name' => 'Name',
    'active' => 'Active',
    'success' => [
        'created' => 'Unit created successfully',
        'updated' => 'Unit updated successfully',
        'deleted' => 'Unit deleted successfully',
    ],
    'error' => [
        'load' => 'Failed to load units',
        'create' => 'Failed to create unit',
    ]
];
```

### Boas Práticas
1. Organização hierárquica de chaves
2. Separação por contexto (success, error, validation)
3. Suporte a múltiplos idiomas
4. Reutilização de labels comuns

## Princípios SOLID

### Single Responsibility Principle (SRP)
- UnitService: Responsável pela lógica de negócio
- UnitRepository: Responsável pelo acesso a dados
- UnitController: Responsável pela coordenação de requisições

### Open/Closed Principle (OCP)
- Extensibilidade via injeção de dependência
- Uso de interfaces para abstração

### Liskov Substitution Principle (LSP)
- Implementação consistente de métodos em repositories
- Manutenção de contratos em services

### Interface Segregation Principle (ISP)
- Interfaces específicas para cada tipo de operação
- Métodos coesos e bem definidos

### Dependency Inversion Principle (DIP)
- Injeção de dependências via construtor
- Uso de abstrações em vez de implementações concretas

## Modelos

### Estrutura
```php
class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'address',
        'city',
    ];

    protected $table = 'units';

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
```

### Boas Práticas
1. Definição clara de atributos fillable
2. Relacionamentos tipados
3. Uso de traits quando necessário
4. Nomenclatura consistente
5. Documentação de relacionamentos

## Convenções de Nomenclatura

### Geral
- Classes: PascalCase (UnitController, UnitService)
- Métodos: camelCase (getUnits, createUnit)
- Variáveis: camelCase (unitData, companyId)
- Constantes: UPPER_SNAKE_CASE (MAX_UNITS)

### Arquivos
- Controllers: PascalCase (UnitController.php)
- Models: PascalCase (Unit.php)
- Services: PascalCase (UnitService.php)
- Repositories: PascalCase (UnitRepository.php)
- Views: kebab-case (create-unit.blade.php)

## Boas Práticas Gerais

### Código
1. Type hints e return types
2. Documentação PHPDoc
3. Tratamento de exceções
4. Validação de dados
5. Logging de erros

### Arquitetura
1. Separação clara de responsabilidades
2. Injeção de dependências
3. Uso de interfaces
4. Testabilidade
5. Manutenibilidade

### Segurança
1. Validação de dados
2. Autenticação e autorização
3. Proteção contra CSRF
4. Sanitização de inputs
5. Logging de ações sensíveis 