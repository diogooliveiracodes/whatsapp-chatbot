# Guia para Configuração do WhatsApp Cloud API

## Integração com a sua plataforma

### 1. Criar conta no Meta for Developers

1. Acesse: https://developers.facebook.com/
2. Clique em **Começar**
3. Entre com sua conta do Facebook usada pela empresa e aceite os termos

### 2. Criar um Aplicativo

1. No menu superior, clique em **Meus Apps > Criar App**
2. Escolha o tipo de app: **Negócios**
3. Preencha o nome do app (ex: Minha Empresa WhatsApp) e finalize

### 3. Ativar o produto WhatsApp

1. Dentro do app recém-criado, clique em **Adicionar Produto**
2. Selecione **WhatsApp** e clique em **Configurar**

### 4. Conectar uma conta do WhatsApp Business

1. Se você ainda não tiver uma conta de WhatsApp Business (WABA), o sistema vai pedir para criar
2. Adicione e verifique o número real da sua empresa
   - **Importante**: A Meta fornece um número de teste, mas ele não deve ser usado em produção

### 5. Gerar credenciais

Dentro do menu **WhatsApp > Configurações**, você verá:
- **Phone Number ID** → ID do número conectado
- **Business Account ID** → ID da conta de WhatsApp Business

Agora precisamos gerar o **Token Permanente**:

1. Vá em **Business Manager > Configurações de Negócios**
2. Clique em **Usuários do Sistema**
3. Crie um novo usuário do sistema
4. Atribua as permissões do WhatsApp
5. Clique em **Gerar Token** e copie com segurança

### 6. Configurar Webhook

1. Dentro do Meta Developers, vá em **Configurações do Webhook**
2. Clique em **Configurar Webhook**
3. Insira a URL fornecida pela plataforma:
   ```
   https://sua-plataforma.com/webhook/whatsapp
   ```
4. Insira também o **Token de Verificação** fornecido
5. Selecione os eventos que deseja receber (ex: messages)

### 7. Entregar credenciais para a plataforma

Depois de configurar tudo, envie para a equipe de integração:
- **Token Permanente** (System User Token)
- **Phone Number ID**
- **Business Account ID**

## Funcionalidades disponíveis

Com isso, a plataforma já conseguirá:
- ✅ Receber mensagens enviadas ao seu WhatsApp
- ✅ Responder automaticamente consumidores
- ✅ Enviar links personalizados de cadastro e agendamento