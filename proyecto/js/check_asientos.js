// checkSession.js
document.addEventListener('DOMContentLoaded', () => {
    fetch('recuperar_datos_user.php', {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
                document.getElementById('user-info').textContent = `Hola, ${data.name}`;
                window.sessionData = data; // Almacena los datos del usuario en una variable global
            } else {
                window.location.href = 'inicio_sesion.html'; // Redirige si no está logueado
            }
        })
        .catch(error => console.error('Error fetching user info:', error));
});
