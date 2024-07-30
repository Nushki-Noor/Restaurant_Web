document.addEventListener('DOMContentLoaded', function () {
    var username = "<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>";
    
    if (username) {
        document.getElementById('login-btn').style.display = 'none';
        document.getElementById('signup-btn').style.display = 'none';
        document.getElementById('logout-btn').style.display = 'inline';
        document.getElementById('username').style.display = 'inline';
        document.getElementById('username').textContent = username;
    } else {
        document.getElementById('login-btn').style.display = 'inline';
        document.getElementById('signup-btn').style.display = 'inline';
        document.getElementById('logout-btn').style.display = 'none';
        document.getElementById('username').style.display = 'none';
    }
});


