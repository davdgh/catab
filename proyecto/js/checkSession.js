document.addEventListener('DOMContentLoaded', () => {
    fetch('http://localhost/proyecto/checkSession.php')
    .then(response => response.json())
    .then(data => {
        const userInfo = document.getElementById('user-info');
        if (data.loggedin) {
            userInfo.textContent = `Hola, ${data.nombre}`;
        } else {
            userInfo.innerHTML = '<a href="inicio_sesion.html">Iniciar sesión</a>';
        }
    })
    .catch(error => {
        console.error('Error al verificar la sesión:', error);
    });
});
