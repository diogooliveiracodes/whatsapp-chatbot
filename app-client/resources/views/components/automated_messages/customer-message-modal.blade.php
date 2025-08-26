<!-- Modal para seleção de mensagens automatizadas para clientes inativos -->
<div id="customerMessageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('automated-messages.select_message') }}
                </h3>
                <button onclick="closeCustomerMessageModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Loading -->
            <div id="customerMessageModalLoading" class="text-center py-8">
                <svg class="animate-spin h-8 w-8 text-blue-500 mx-auto" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ __('automated-messages.loading_messages') }}</p>
            </div>

            <!-- Messages List -->
            <div id="customerMessageModalContent" class="hidden">
                <div id="customerMessagesList" class="space-y-2 max-h-64 overflow-y-auto">
                    <!-- Messages will be loaded here -->
                </div>

                <!-- No messages message -->
                <div id="customerNoMessagesMessage" class="hidden text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('automated-messages.no_messages_available') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('automated-messages.create_message_first') }}</p>
                    <div class="mt-6">
                        <a href="{{ route('automated-messages.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('automated-messages.create_message') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button onclick="closeCustomerMessageModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    {{ __('automated-messages.cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentCustomerData = null;

function openCustomerMessageModal(customerName, customerPhone, unitName, companyName) {
    currentCustomerData = {
        customerName,
        customerPhone,
        unitName,
        companyName
    };

    // Show modal and loading
    document.getElementById('customerMessageModal').classList.remove('hidden');
    document.getElementById('customerMessageModalLoading').classList.remove('hidden');
    document.getElementById('customerMessageModalContent').classList.add('hidden');

    // Load messages
    loadCustomerAutomatedMessages();
}

function closeCustomerMessageModal() {
    document.getElementById('customerMessageModal').classList.add('hidden');
    currentCustomerData = null;
}

function loadCustomerAutomatedMessages() {
    // Get unit ID from current user
    const unitId = {{ auth()->user()->unit_id }};

    fetch(`{{ route('automated-messages.get-by-unit') }}?unit_id=${unitId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('customerMessageModalLoading').classList.add('hidden');
            document.getElementById('customerMessageModalContent').classList.remove('hidden');

            if (data.messages && data.messages.length > 0) {
                displayCustomerMessages(data.messages);
                document.getElementById('customerNoMessagesMessage').classList.add('hidden');
            } else {
                document.getElementById('customerMessagesList').innerHTML = '';
                document.getElementById('customerNoMessagesMessage').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error loading messages:', error);
            document.getElementById('customerMessageModalLoading').classList.add('hidden');
            document.getElementById('customerMessageModalContent').classList.remove('hidden');
            document.getElementById('customerNoMessagesMessage').classList.remove('hidden');
        });
}

function displayCustomerMessages(messages) {
    const messagesList = document.getElementById('customerMessagesList');
    messagesList.innerHTML = '';

    messages.forEach(message => {
        const messageElement = document.createElement('div');
        messageElement.className = 'p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200';
        messageElement.onclick = () => sendCustomerMessage(message);

        messageElement.innerHTML = `
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">${message.name}</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">${message.type_label}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-2 line-clamp-2">${message.content}</p>
                </div>
                <div class="ml-3 flex-shrink-0">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                    </svg>
                </div>
            </div>
        `;

        messagesList.appendChild(messageElement);
    });
}

function sendCustomerMessage(message) {
    if (!currentCustomerData) return;

    // Process message with customer data (without schedule data)
    const processedMessage = processCustomerMessageContent(message.content, currentCustomerData);

    // Get WhatsApp number from unit settings
    const whatsappNumber = '{{ auth()->user()->unit->unitSettings->whatsapp_number ?? "" }}';

    if (!whatsappNumber) {
        alert('{{ __("automated-messages.whatsapp_not_configured") }}');
        return;
    }

    // Format customer phone number for WhatsApp
    let phone = formatPhoneForWhatsApp(currentCustomerData.customerPhone);

    if (!phone) {
        alert('{{ __("automated-messages.invalid_phone") }}');
        return;
    }

    // Create WhatsApp URL
    const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(processedMessage)}`;

    // Open WhatsApp
    window.open(whatsappUrl, '_blank');

    // Close modal
    closeCustomerMessageModal();
}

function formatPhoneForWhatsApp(phone) {
    if (!phone) {
        return null;
    }

    if (typeof phone !== 'string') {
        return null;
    }

    // Remove all non-digit characters
    let cleanPhone = phone.replace(/\D/g, '');

    // Handle different Brazilian phone formats
    if (cleanPhone.length === 11) {
        // Format: 11999999999 (Brazilian mobile with 9)
        return '55' + cleanPhone;
    } else if (cleanPhone.length === 10) {
        // Format: 1199999999 (Brazilian landline without 9)
        return '55' + cleanPhone;
    } else if (cleanPhone.length === 13 && cleanPhone.startsWith('55')) {
        // Already in international format
        return cleanPhone;
    } else if (cleanPhone.length === 12 && cleanPhone.startsWith('55')) {
        // Already in international format
        return cleanPhone;
    }

    // If none of the above formats match, return null
    return null;
}

function processCustomerMessageContent(content, data) {
    return content
        .replace(/{customer_name}/g, data.customerName || '')
        .replace(/{customer_phone}/g, data.customerPhone || '')
        .replace(/{unit_name}/g, data.unitName || '')
        .replace(/{company_name}/g, data.companyName || '');
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('customerMessageModal');
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeCustomerMessageModal();
        }
    });
});
</script>
