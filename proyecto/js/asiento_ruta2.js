document.addEventListener('DOMContentLoaded', () => {
    const asientosContainer = document.querySelector('.asientos');
    const btnEnviar = document.querySelector('.btn-enviar');
    const tipoClienteSelect = document.getElementById('tipo-cliente');
    const precioDisplay = document.getElementById('precio');
    const tarifas = {
        'estudiante': 40.00, // Estudiante
        'adulto_mayor': 25.00, // Adulto Mayor
        'completo': 55.00  // Completo
    };

    // Generar asientos dinámicamente
    let columnasAsientos = 0;
    let columnaContainer = document.createElement('div');
    columnaContainer.className = "column";
    for (let i = 1; i <= 30; i++) {   
        const asiento = document.createElement('div');
        asiento.className = 'asiento';
        asiento.id = `seat-${i}`;
        asiento.textContent = i;
        columnaContainer.appendChild(asiento)
        if(columnasAsientos) {
            asientosContainer.appendChild(columnaContainer);
            columnaContainer = document.createElement('div');
            columnaContainer.className = "column";
            columnasAsientos = 0;
        }else {
            columnasAsientos++;
        }
    }

    const seats = document.querySelectorAll('.asiento');

    // Obtener asientos reservados al cargar la página
    fetch('obtenasiento2.php')
        .then(response => response.json())
        .then(data => {
            data.reservedSeats.forEach(seatId => {
                const seat = document.getElementById(`seat-${seatId}`);
                if (seat) {
                    seat.classList.add('reserved'); // Clase para asientos reservados
                }
            });
        });

    seats.forEach(seat => {
        seat.addEventListener('click', () => {
            if (!seat.classList.contains('reserved')) { // Solo permitir seleccionar si no está reservado
                seat.classList.toggle('selected');
                actualizarPrecio();
            }
        });
    });

    btnEnviar.addEventListener('click', () => {
        const selectedSeats = document.querySelectorAll('.asiento.selected');
        if (selectedSeats.length > 0) {
            fetch('checkSession.php')
            .then(respuesta => respuesta.json())
            .then(respuesta => {
                const seatsText = Array.from(selectedSeats).map(seat => seat.textContent).join(', ');
                const tipoCliente = respuesta.tipo_cliente;
                const precio = (tarifas[tipoCliente] || 0) * selectedSeats.length;
                Swal.fire({
                    title: 'Resumen de Reserva',
                    html: `
                        <p>Nombre: ${respuesta.nombre}</p>
                        <p>Correo: ${respuesta.correo}</p>
                        <p>Has seleccionado los siguientes asientos: ${seatsText}.</p>
                        <p>Tipo de Cliente: ${respuesta.tipo_cliente}</p>
                        <p>Precio Total: $${precio.toFixed(2)}</p>
                        <p>Se enviará un correo de confirmación.</p>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Confirmar Reserva',
                    cancelButtonText: 'Cancelar',
                    showCancelButton: true,
                    preConfirm: () => {
                        // Aquí podrías agregar el código para procesar la compra
                        // Si el procesamiento es exitoso, puedes mostrar un mensaje de éxito y limpiar la selección de asientos
                        fetch('compra1_ruta2.php', {
                            method: 'POST',
                            headers: new Headers(),
                            body: JSON.stringify({
                                // Enviar los datos de los asientos seleccionados
                                selectedSeats: Array.from(selectedSeats).map(seat => seat.textContent)
                            }),
                            cache: 'no-cache',
                            mode: 'cors'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(!data.success) {
                                Swal.fire({
                                    title: 'Error',
                                    icon: 'error',
                                    text: data.message
                                })
                                return;
                            }
                            Swal.fire({
                                title: "Compra Confirmada",
                                icon: "success",
                                html: `Gracias por tu compra.<br>Has reservado los asientos: ${seatsText}.<br>Precio total: $${precio.toFixed(2)}.<br>Se enviará un correo de confirmación.`
                            });
                            // Resetear selección de asientos
                            document.querySelectorAll('.asiento.selected').forEach(seat => seat.classList.remove('selected'));
                            actualizarPrecio();
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error',
                                text: 'Hubo un error al procesar la reserva.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        });
                    }
                });
            })
        } else {
            Swal.fire({
                title: "Error",
                icon: "warning",
                text: "Ningún asiento seleccionado.",
                confirmButtonText: 'Aceptar'
            });
        }
    });

    function actualizarPrecio() {
        const tipoCliente = tipoClienteSelect.value;
        const selectedSeats = document.querySelectorAll('.asiento.selected');
        const precio = (tarifas[tipoCliente] || 0) * selectedSeats.length;
        precioDisplay.textContent = `Precio: $${precio.toFixed(2)}`;
    }

    // Inicializar el precio
    actualizarPrecio();
});
