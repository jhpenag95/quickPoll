function validarFormulario(event) {
    event.preventDefault(); // Evita que el formulario se envíe por defecto

    // Obtener los valores de los campos del formulario
    const nombre = $('#nombre').val();
    const email = $('#email').val();
    const rol = $('#rol').val();
    const estado = $('#estado').val();
    const password = $('#password').val();
    const password_confirmation = $('#password-confirm').val();
    const idEmpresa = $('#idEmpresa').val();

    // Validar que los campos obligatorios no estén vacíos
    if (!nombre || !email || !password || !password_confirmation) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            html: '<ul style="list-style-type: none;"><li>' + 'Por favor, complete todos los campos obligatorios.' + '</li></ul>',
            confirmButtonColor: "#27ae60",
            confirmButtonText: "OK"
        });
        return;
    }

    // Validar que la contraseña y la confirmación de contraseña coincidan
    if (password !== password_confirmation) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            html: '<ul style="list-style-type: none;"><li>' + 'Las contraseñas no coinciden' + '</li></ul>',
            confirmButtonColor: "#27ae60",
            confirmButtonText: "OK"
        });
        return;
    }

    // Si todas las validaciones pasan, puedes enviar el formulario
    if (nombre && email && password && password_confirmation) {
        var data = {
            nombre: nombre,
            email: email,
            rol: rol,
            estado: estado,
            password: password,
            password_confirmation: password_confirmation,
            idEmpresa: idEmpresa
        };

        registrarUsuario(data);
    }
}

function registrarUsuario(data) {
    $.ajax({
        url: '/usuario/registrar',
        type: 'POST',
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.status === 'success') {
                window.location.href = '/usuarios';
            }
        },
        error: function (response) {
            const mensajes = [];
            const json = response?.responseJSON;

            if (json?.errors) {
                Object.values(json.errors).forEach(err => {
                    mensajes.push(...(Array.isArray(err) ? err : [err]));
                });
            } else if (json?.message) {
                mensajes.push(json.message);
            } else {
                mensajes.push(response?.responseText || 'Se produjo un error.');
            }

            Swal.fire({
                icon: "error",
                title: "Oops...",
                html: '<ul style="list-style-type: none;"><li>' + mensajes.join('</li><li>') + '</li></ul>',
                confirmButtonColor: "#27ae60",
                confirmButtonText: "OK"
            });
        }
    });
}

