document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();

        if (!email || !password) {
            Swal.fire({
                title: 'Error',
                text: 'Todos los campos son obligatorios.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        fetch('http://localhost/proyecto/login.php', {
            method: 'POST',
            headers: new Headers(),
            body: new URLSearchParams({
                email,
                password
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Bienvenido',
                    text: `Hola, ${data.nombre}!`,
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al iniciar sesión. Inténtalo de nuevo.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            console.error(`Error: ${error}`)
        });
    });
});
