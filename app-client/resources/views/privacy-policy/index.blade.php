<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Política de Privacidade do Império Agendamentos - Saiba como protegemos seus dados pessoais">

    <title>Política de Privacidade - Império Agendamentos</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900">Império Agendamentos</h1>
                    </div>
                    <div class="text-sm text-gray-500">
                        Última atualização: {{ date('d/m/Y') }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white shadow-sm rounded-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Política de Privacidade</h2>

                <div class="prose prose-lg max-w-none">
                    <p class="text-gray-600 mb-6">
                        Esta Política de Privacidade descreve como o Império Agendamentos ("nós", "nosso" ou "a empresa") coleta, usa e protege suas informações pessoais quando você utiliza nosso serviço de agendamentos via WhatsApp Business.
                    </p>

                    <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">1. Informações que Coletamos</h3>
                    <p class="text-gray-600 mb-4">
                        Coletamos as seguintes informações quando você utiliza nosso serviço:
                    </p>
                    <ul class="list-disc pl-6 text-gray-600 mb-6">
                        <li>Nome completo</li>
                        <li>Número de telefone</li>
                        <li>Endereço de e-mail (quando fornecido)</li>
                        <li>Informações sobre agendamentos e preferências de serviço</li>
                        <li>Histórico de conversas via WhatsApp</li>
                        <li>Dados de uso do aplicativo</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">2. Como Usamos Suas Informações</h3>
                    <p class="text-gray-600 mb-4">
                        Utilizamos suas informações pessoais para:
                    </p>
                    <ul class="list-disc pl-6 text-gray-600 mb-6">
                        <li>Processar e gerenciar seus agendamentos</li>
                        <li>Enviar confirmações e lembretes de agendamentos</li>
                        <li>Fornecer suporte ao cliente</li>
                        <li>Melhorar nossos serviços</li>
                        <li>Cumprir obrigações legais</li>
                        <li>Enviar comunicações sobre nossos serviços (com seu consentimento)</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">3. Compartilhamento de Informações</h3>
                    <p class="text-gray-600 mb-6">
                        Não vendemos, alugamos ou compartilhamos suas informações pessoais com terceiros, exceto:
                    </p>
                    <ul class="list-disc pl-6 text-gray-600 mb-6">
                        <li>Com prestadores de serviços que nos ajudam a operar nossa plataforma</li>
                        <li>Quando exigido por lei ou ordem judicial</li>
                        <li>Para proteger nossos direitos e segurança</li>
                        <li>Com WhatsApp Business API (conforme necessário para o funcionamento do serviço)</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">4. Segurança dos Dados</h3>
                    <p class="text-gray-600 mb-6">
                        Implementamos medidas de segurança técnicas e organizacionais apropriadas para proteger suas informações pessoais contra acesso não autorizado, alteração, divulgação ou destruição.
                    </p>

                    <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">5. Retenção de Dados</h3>
                    <p class="text-gray-600 mb-6">
                        Mantemos suas informações pessoais apenas pelo tempo necessário para cumprir os propósitos descritos nesta política, a menos que um período de retenção mais longo seja exigido ou permitido por lei.
                    </p>

                    <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">6. Seus Direitos</h3>
                    <p class="text-gray-600 mb-4">
                        Você tem os seguintes direitos em relação às suas informações pessoais:
                    </p>
                    <ul class="list-disc pl-6 text-gray-600 mb-6">
                        <li>Acessar suas informações pessoais</li>
                        <li>Corrigir informações imprecisas</li>
                        <li>Solicitar a exclusão de suas informações</li>
                        <li>Retirar consentimento para o processamento</li>
                        <li>Portabilidade dos dados</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">7. Cookies e Tecnologias Similares</h3>
                    <p class="text-gray-600 mb-6">
                        Utilizamos cookies e tecnologias similares para melhorar sua experiência, analisar o uso do serviço e personalizar conteúdo. Você pode controlar o uso de cookies através das configurações do seu navegador.
                    </p>

                    <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">8. Menores de Idade</h3>
                    <p class="text-gray-600 mb-6">
                        Nosso serviço não é destinado a menores de 18 anos. Não coletamos intencionalmente informações pessoais de menores de 18 anos. Se você é pai ou responsável e acredita que seu filho nos forneceu informações pessoais, entre em contato conosco.
                    </p>

                    <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">9. Alterações nesta Política</h3>
                    <p class="text-gray-600 mb-6">
                        Podemos atualizar esta Política de Privacidade periodicamente. Notificaremos você sobre quaisquer alterações significativas através do nosso serviço ou por e-mail.
                    </p>

                    <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">10. Contato</h3>
                    <p class="text-gray-600 mb-4">
                        Se você tiver dúvidas sobre esta Política de Privacidade ou sobre como tratamos suas informações pessoais, entre em contato conosco:
                    </p>
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <p class="text-gray-700"><strong>Império Sistemas</strong></p>
                        <p class="text-gray-600">E-mail: privacidade@imperiosistemas.com</p>
                        <p class="text-gray-600">WhatsApp: (31) 99669-5467</p>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">11. Conformidade com WhatsApp Business</h3>
                    <p class="text-gray-600 mb-6">
                        Esta política está em conformidade com as diretrizes do WhatsApp Business API. Utilizamos o WhatsApp Business API para facilitar a comunicação e agendamentos, sempre respeitando as políticas de privacidade do WhatsApp e as leis de proteção de dados aplicáveis.
                    </p>

                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-500">
                            Esta política foi criada em conformidade com a Lei Geral de Proteção de Dados (LGPD) e as diretrizes do WhatsApp Business API.
                        </p>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t mt-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="text-center text-sm text-gray-500">
                    <p>&copy; {{ date('Y') }} Império Sistemas. Todos os direitos reservados.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
