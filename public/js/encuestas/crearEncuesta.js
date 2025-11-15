
//al cambiar el tipo de respuesta, actualizar la vista previa
$(document).on('change', '#preguntas-container select.tipo-respuesta', function () {
    actualizarTipoRespuesta(this);
});

//al hacer clic en agregar opcion, agregar una opcion al contenedor de opciones
$(document).on('click', '#preguntas-container .agregar-opcion', function () {
    var item = $(this).closest('.pregunta-item');
    agregarOpcion(item);
});

//al hacer clic en eliminar opcion, eliminar la opcion del contenedor de opciones
$(document).on('click', '#preguntas-container .eliminar-opcion', function () {
    var item = $(this).closest('.pregunta-item');
    $(this).closest('.respuesta-item').remove();
    renumerarOpciones(item);
});

//al hacer clic en eliminar pregunta, eliminar la pregunta del contenedor de preguntas
$(document).on('click', '#preguntas-container .eliminar-pregunta', function () {
    $(this).closest('.pregunta-item').remove();
    renumerarPreguntas();
});

//al hacer clic en agregar pregunta, agregar una pregunta al contenedor de preguntas
function agregarPregunta() {
    var container = $('#preguntas-container');
    var index = container.children('.pregunta-item').length + 1;
    var nueva = '<div class="pregunta-item">' +
        '<div class="form-group" id="contenedor-numero">' +
        '<label>Pregunta ' + index + ' *</label>' +
        '<input type="text" name="pregunta-' + index + '" placeholder="Escribe tu pregunta aquí" required>' +
        '</div>' +
        '<div class="form-group" id="contenedor-tipo-respuesta">' +
        '<label>Tipo de Respuesta</label>' +
        '<select name="tipo-respuesta-' + index + '" class="tipo-respuesta">' +
        '<option value="opcion-multiple">Opción Múltiple</option>' +
        '<option value="texto-corto">Texto Corto</option>' +
        '<option value="texto-largo">Texto Largo</option>' +
        '<option value="escala">Escala (1-5)</option>' +
        '<option value="si-no">Sí/No</option>' +
        '</select>' +
        '</div>' +
        '<div class="form-group" id="contenedor-opciones"></div>' +
        '<button type="button" class="btn btn-danger eliminar-pregunta" style="margin-top: 10px;">Eliminar Pregunta</button>' +
        '</div>';
    container.append(nueva);

    // Inicializar vista previa de la nueva pregunta según el tipo seleccionado
    var nuevoItem = container.children('.pregunta-item').last();
    // Inicializar la vista previa de la nueva pregunta
    var select = nuevoItem.find('#contenedor-tipo-respuesta select.tipo-respuesta');
    if (select.length) {
        actualizarTipoRespuesta(select[0]);
    }
}

function actualizarTipoRespuesta(selectEl) {
    // Obtener el elemento select y el contenedor de opciones
    var select = $(selectEl);
    var item = select.closest('.pregunta-item');
    var opciones = item.find('#contenedor-opciones');
    var tipo = select.val();
    opciones.empty();


    if (tipo === 'opcion-multiple') {
        opciones.append('<label>Opciones de Respuesta (para opción múltiple)</label>');
        opciones.append('<div class="opciones-list"></div>');
        opciones.append('<button type="button" class="btn btn-secondary agregar-opcion" style="margin-left: 20px; margin-top: 10px;">+ Agregar Opción</button>');
        agregarOpcion(item);
        agregarOpcion(item);
    } else if (tipo === 'texto-corto') {
        opciones.append('<label>Vista previa</label>');
        opciones.append('<input type="text" placeholder="Respuesta corta">');
    } else if (tipo === 'texto-largo') {
        opciones.append('<label>Vista previa</label>');
        opciones.append('<textarea placeholder="Respuesta larga"></textarea>');
    } else if (tipo === 'escala') {
        var escala = '<label>Vista previa</label><div class="escala">' +
            '<label style="margin-right:8px;">1</label>' +
            '<input type="radio">' +
            '<label style="margin:0 8px;">2</label>' +
            '<input type="radio">' +
            '<label style="margin:0 8px;">3</label>' +
            '<input type="radio">' +
            '<label style="margin:0 8px;">4</label>' +
            '<input type="radio">' +
            '<label style="margin-left:8px;">5</label>' +
            '<input type="radio">' +
            '</div>';
        opciones.append(escala);
    } else if (tipo === 'si-no') {
        opciones.append('<label>Vista previa</label>');
        opciones.append('<select><option>Sí</option><option>No</option></select>');
    }
}

//al hacer clic en agregar opcion, agregar una opcion al contenedor de opciones
function agregarOpcion(item) {
    var index = item.index() + 1; // Obtener el índice de la pregunta + 1
    var list = item.find('.opciones-list'); // Obtener la lista de opciones
    var count = list.children('.respuesta-item').length + 1; // Obtener el número de opciones + 1

    // Crear el HTML para la nueva opción
    var html = '<div class="respuesta-item">' +
        '<input type="text" name="opcion-' + index + '-' + count + '" placeholder="Opción ' + count + '">' +
        '<button type="button" class="btn btn-danger eliminar-opcion" style="padding: 5px 10px;">X</button>' +
        '</div>';
    list.append(html);// Agregar la nueva opción al contenedor
}

//En la función renumerarPreguntas, renumerar las preguntas y sus opciones
function renumerarPreguntas() {
    $('#preguntas-container .pregunta-item').each(function (i) {
        var idx = i + 1;
        $(this).find('#contenedor-numero label').text('Pregunta ' + idx + ' *');
        $(this).find('input[name^="pregunta-"]').attr('name', 'pregunta-' + idx);
        $(this).find('select.tipo-respuesta').attr('name', 'tipo-respuesta-' + idx);
        renumerarOpciones($(this));
    });
}

//En la función renumerarOpciones, renumerar las opciones de una pregunta
function renumerarOpciones(item) {
    var idx = item.index() + 1;
    item.find('.respuesta-item').each(function (i) {
        var optIdx = i + 1;
        $(this).find('input[type="text"]').attr('name', 'opcion-' + idx + '-' + optIdx).attr('placeholder', 'Opción ' + optIdx);
    });
}

//Esto es para inicializar la primera pregunta con su tipo de respuesta
$(function () {
    var first = $('#preguntas-container .pregunta-item').first();
    var select = first.find('#contenedor-tipo-respuesta select');
    if (select.length) {
        actualizarTipoRespuesta(select[0]);
    }
});

function guardarEncuesta(event) {
    event.preventDefault();
    $('#estado').val('ACTIVA');
    var form = $(event.target).closest('form');
    form.submit();
}
