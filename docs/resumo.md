## Resumo das alterações para upload e exibição de imagem (Unidades)

Este documento resume as mudanças realizadas para implementar upload/remoção de imagem, exibição e responsividade nas telas de Unidade. Use como guia para replicar em outras entidades.

### Backend

- Controller de imagem
  - `App\Http\Controllers\ImageController`
    - Upload: valida `image` e `directory`, usa o serviço de storage e retorna JSON `{ image_name, image_path }`.
    - Delete: recebe `image_path` e apaga no storage.
    - Melhorias:
      - Retorna 422 com `errors` em validações (ValidationException).
      - Retorna 500 com `message` real para facilitar depuração.
      - Verifica `UploadedFile::isValid()` antes da validação e retorna erro do PHP se existir.

- Serviço de storage
  - `App\Services\Storage\ImageStoreService::uploadImage(Request $request)`
    - Sobe o arquivo para S3 em `public/upload/files/{company_id}/{directory}`.
    - Resposta: `{ image_name, image_path }`.

- Rotas (já existentes)
  - `POST /upload-image` → `ImageController@upload` (nome: `upload-image`)
  - `DELETE /delete-image` → `ImageController@delete` (nome: `delete-image`)

- Modelos e Requests
  - Model: adicionar `image_name` e `image_path` ao `$fillable` da entidade (ex.: `App\Models\Unit`).
  - Requests (Store/Update): permitir `image_name` e `image_path` (string, tamanhos adequados) e incluir em `attributes`/`messages`.

### Frontend (Blade + Tailwind + JS)

- Componente reutilizável de upload
  - Arquivo: `resources/views/components/global/image-upload.blade.php`
  - Responsável por toda a UI/UX e JS (upload, validação, alternância selecionar/remover, prévia e mensagens).
  - Props principais:
    - `directory` (obrigatório): diretório de destino no S3 (ex.: `units`, `users`).
    - `nameImageName` (opcional): nome do input hidden para o nome do arquivo. Padrão: `image_name`.
    - `nameImagePath` (opcional): nome do input hidden para o caminho no storage. Padrão: `image_path`.
    - `initialImageName`, `initialImagePath` (opcionais): valores iniciais.
    - `label`, `help`, `selectText`, `removeText` (opcionais): textos.
    - `avatarSize` (opcional): tamanho da prévia (ex.: `h-16 w-16`).
    - `successColor` (opcional): cor do botão de selecionar (ex.: `green`).

- Funcionamento (embutido no componente)
  - Input file oculto (`sr-only`) e botão "Selecionar imagem" (success).
  - Prévia circular (rounded-full, object-cover) e botão "Remover imagem" no mesmo slot.
  - Oculta texto de ajuda quando há imagem.
  - Valida tipo e tamanho (2MB) no client.
  - Chama `upload-image` (POST) e `delete-image` (DELETE). Preenche `image_name`/`image_path` em inputs hidden.

- Telas atualizadas (Unidade)
  - `resources/views/units/create.blade.php`
    - Grid mobile-first, card de imagem antes do nome, usando o componente:
      <x-global.image-upload directory="units"
                             :initialImageName="old('image_name')"
                             :initialImagePath="old('image_path')"
                             nameImageName="image_name"
                             nameImagePath="image_path" />
  - `resources/views/units/edit.blade.php`
    - Mesmo padrão, iniciando com prévia quando houver imagem:
      <x-global.image-upload directory="units"
                             :initialImageName="old('image_name', $unit->image_name)"
                             :initialImagePath="old('image_path', $unit->image_path)"
                             nameImageName="image_name"
                             nameImagePath="image_path" />
  - `resources/views/units/index.blade.php`
    - Removida a coluna "Ativo".
    - Avatar circular ao lado do nome (desktop e mobile). Fallback: inicial do nome quando sem imagem.
  - `resources/views/units/show.blade.php`
    - Exibição do avatar circular 150x150, centralizado no mobile (`mx-auto md:mx-0`).

### Traduções adicionadas/ajustadas

- `resources/lang/pt_BR/units.php`
  - `image`, `image_help`, `error.image_upload`, `attributes.image_name`, `attributes.image_path`.
- `resources/lang/en/units.php`
  - Equivalentes em inglês.
- `resources/lang/*/actions.php`
  - `select_image`, `remove_image`, `remove`.

### Passo a passo para replicar em outra entidade

1) Banco/Modelo
   - (Se necessário) Adicionar colunas `image_name` (string, nullable) e `image_path` (string, nullable) na migration da entidade.
   - Adicionar `image_name` e `image_path` ao `$fillable` do Model correspondente.

2) Requests
   - Incluir `image_name` e `image_path` em `rules`, `attributes` e `messages` de `Store{Entity}Request` e `Update{Entity}Request`.

3) Views
   - Usar o componente `x-global.image-upload` no formulário de create/edit da entidade.
   - Exemplo (Users):
     <x-global.image-upload directory="users"
                            :initialImageName="old('image_name', $user->image_name ?? null)"
                            :initialImagePath="old('image_path', $user->image_path ?? null)"
                            nameImageName="image_name"
                            nameImagePath="image_path" />
   - Exibir a imagem em listagens/detalhes (usar `Storage::disk('s3')->url($path)`).

### Snippets úteis

Gerar URL pública no Blade:
```php
{{ Storage::disk('s3')->url($model->image_path) }}
```

Headers para fetch (upload):
```js
fetch("{{ route('upload-image') }}", {
  method: 'POST',
  headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
  body: formData,
});
```

Headers para fetch (delete):
```js
fetch("{{ route('delete-image') }}", {
  method: 'DELETE',
  headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
  body: JSON.stringify({ image_path }),
});
```

### Observações

- A UI foi construída mobile-first e a lógica de alternância/erros está encapsulada no componente.
- Para outras entidades, apenas insira o componente, ajuste o `directory` e garanta que os campos `image_name` e `image_path` sejam persistidos.


