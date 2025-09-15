@props([
    'position' => 'bottom-6 right-6',
    'color' => 'indigo',
    'size' => 'p-3',
    'iconSize' => 'w-6 h-6'
])

@php
    $uniqueId = 'scroll-to-top-' . uniqid();
@endphp

<!-- Scroll to top button -->
<div id="{{ $uniqueId }}" class="scroll-to-top-button fixed {{ $position }} z-50 hidden">
    <button type="button"
            class="scroll-to-top-btn bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 text-white rounded-full {{ $size }} shadow-lg transition-all duration-300 transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
        <svg class="{{ $iconSize }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>
</div>

<script>
    (function() {
        // Check if already initialized
        if (window.scrollToTopInitialized) return;
        window.scrollToTopInitialized = true;

        // Add click event listeners to all scroll buttons
        function addScrollButtonListeners() {
            const buttons = document.querySelectorAll('.scroll-to-top-btn');
            buttons.forEach(function(button) {
                button.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            });
        }

        // Show/hide scroll buttons based on scroll position
        function handleScroll() {
            const buttons = document.querySelectorAll('.scroll-to-top-button');
            buttons.forEach(function(button) {
                if (window.scrollY > 300) {
                    button.classList.remove('hidden');
                    button.classList.add('block');
                } else {
                    button.classList.add('hidden');
                    button.classList.remove('block');
                }
            });
        }

        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                addScrollButtonListeners();
                window.addEventListener('scroll', handleScroll);
            });
        } else {
            addScrollButtonListeners();
            window.addEventListener('scroll', handleScroll);
        }
    })();
</script>
