document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registrationForm');
    
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        // Obtener valores del formulario
        const nombre = document.getElementById('nombre').value.trim();
        const email = document.getElementById('email').value.trim();
        const telefono = document.getElementById('telefono').value.trim();
        const password = document.getElementById('password').value.trim();
        const tipoCliente = document.getElementById('tipo_cliente').value.trim();

        // Verificar si todos los campos están llenos
        if (!nombre || !email || !telefono || !password || !tipoCliente) {
            Swal.fire({
                title: 'Error',
                text: 'Todos los campos son obligatorios.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        // Enviar datos al backend
        fetch('http://localhost/proyecto/register.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                nombre: nombre,
                email: email,
                telefono: telefono,
                password: password,
                tipo_cliente: tipoCliente
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok.');
            }
            return response.text(); // Cambiar a text() para ver la respuesta completa
        })
        .then(data => {
            console.log(data); // Imprimir la respuesta en la consola
            const jsonData = JSON.parse(data);
            if (jsonData.message === 'Usuario registrado exitosamente') {
                Swal.fire({
                    title: 'Registro exitoso',
                    text: '¡Te has registrado correctamente!',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    window.location.href = 'inicio_sesion.html';
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: jsonData.message,
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al registrar. Inténtalo de nuevo.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            console.error('Error:', error);
        });
    });
});
