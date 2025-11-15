function login(event) {
    event.preventDefault();

    const submitButton = loginForm.querySelector('button[type="submit"]');

    // Mostrar estado de carga
    submitButton.disabled = true;
    submitButton.innerHTML = 'Iniciando sesión...';

    var email = $('#email').val();
    var password = $('#password').val();

    if (!email || !password) {
        Swal.fire({
            icon: "warning",
            title: "Campos requeridos",
            html: '<ul style="list-style-type: none;"><li>Ingresa tu correo y contraseña.</li></ul>',
            confirmButtonColor: "#27ae60",
            confirmButtonText: "OK"
        });
        submitButton.disabled = false;
        submitButton.innerHTML = 'Iniciar sesión';
        return;
    }

    var data = {
        email: email,
        password: password,
        remember: $('#remember').is(':checked') ? '1' : ''
    };

    // Enviar solicitud de inicio de sesión
    $.ajax({
        url: '/login',
        type: 'POST',
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        success: function (response) {
            if (response && response.success) {
                window.location.href = response.redirect || '/dashboard';
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    html: '<ul style="list-style-type: none;"><li>' + (response && response.message ? response.message : 'Error al iniciar sesión. Por favor, verifica tus credenciales.') + '</li></ul>',
                    confirmButtonColor: "#27ae60",
                    confirmButtonText: "OK"
                });
                submitButton.disabled = false;
                submitButton.innerHTML = 'Iniciar sesión';
            }
        },
        error: function (xhr) {
            const serverMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error al iniciar sesión. Por favor, verifica tus credenciales.';
            Swal.fire({
                icon: "error",
                title: "Oops...",
                html: '<ul style="list-style-type: none;"><li>' + serverMsg + '</li></ul>',
                confirmButtonColor: "#27ae60",
                confirmButtonText: "OK"
            });
            submitButton.disabled = false;
            submitButton.innerHTML = 'Iniciar sesión';
        }
    });

}