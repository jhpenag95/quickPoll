function registroEmpresa(e) {
    e.preventDefault();

    var nombreEmpresa = $('#nombre-empresa').val();
    var nit = $('#nit').val();
    var direccion = $('#direccion').val();
    var telefono = $('#telefono').val();
    var emailEmpresa = $('#email-empresa').val();
    var nombreUser = $('#nombre-user').val();
    var emailUser = $('#email-user').val();
    var password = $('#password').val();
    var passwordConfirm = $('#password-confirm').val();
    var rol = $('#rol').val();

    if (password !== passwordConfirm) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            html: '<ul style="list-style-type: none;"><li>' + 'Las contraseñas no coinciden' + '</li></ul>',
            confirmButtonColor: "#27ae60",
            confirmButtonText: "OK"
        });
        return;
    }

    var data = {
        nombreEmpresa: nombreEmpresa,
        nit: nit,
        direccion: direccion,
        telefono: telefono,
        emailEmpresa: emailEmpresa,
        nombreUser: nombreUser,
        emailUser: emailUser,
        password: password,
        passwordConfirm: passwordConfirm,
        rol: rol
    };
    console.log("data: ", data);

    $.ajax({
        url: '/empresa/registrar',
        type: 'POST',
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.status === 'success') {
                window.location.href = '/';
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
