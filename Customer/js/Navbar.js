document.getElementById('user-icon').addEventListener('click', function() {
    var popup = document.getElementById('user-popup');
    popup.style.display = popup.style.display === 'block' ? 'none' : 'block';
});

window.addEventListener('click', function(event) {
    var popup = document.getElementById('user-popup');
    if (!event.target.matches('.user-icon')) {
        if (popup.style.display === 'block') {
            popup.style.display = 'none';
        }
    }
});
