function registroEmpresa(e) {
    e.preventDefault();
    var nombreEmpresa       = $('#nombre-empresa').value;
    var nit                 = $('#nit').value;
    var direccion           = $('#direccion').value;
    var telefono            = $('#telefono').value;
    var emailEmpresa        = $('#email-empresa').value;
    var nombreAdmin         = $('#nombre-admin').value;
    var emailAdmin          = $('#email-admin').value;
    var password            = $('#password').value;
    var passwordConfirm     = $('#password-confirm').value;

    if (password !== passwordConfirm) {
        alert('Las contraseñas no coinciden');
        return;
    }

    var data = {
        nombreEmpresa: nombreEmpresa,
        nit: nit,
        direccion: direccion,
        telefono: telefono,
        emailEmpresa: emailEmpresa,
        nombreAdmin: nombreAdmin,
        emailAdmin: emailAdmin,
        password: password,
        passwordConfirm: passwordConfirm
    };
    
    $.ajax({
        url: '/empresa/registrar',
        type: 'POST',
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log(response);
            // window.location.href = '/';
        },
        error: function(response) {
            console.log(response);
            // alert('Error al registrar la empresa');
        }
    });    
}
