document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.nav-link.dropdown-toggle').forEach(function(element) {
        element.addEventListener('click', function(event) {
            event.preventDefault();
            const dropdownMenu = this.nextElementSibling;
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        });
    });
});

