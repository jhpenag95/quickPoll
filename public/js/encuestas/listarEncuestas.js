document.addEventListener('DOMContentLoaded', function () {
    new DataTable('#encuestasTableBody', {
        ajax: {
            url: '/encuestas/listarEncuestas',
            type: 'GET',
            dataSrc: ''
        },
        columns: [
            { data: 'idEncuesta', title: 'ID Encuesta'},
            { data: 'nombre', title: 'Nombre' },
            { data: 'cantidad', title: 'Cantidad Respuestas' },
            { data: 'fechaInicio', title: 'Fecha Inicio' },
            { data: 'fechaFin', title: 'Fecha Fin' },   
            {
                data: 'estado', title: 'Estado', render: function (data) {
                    const isActive = String(data || '').toUpperCase() === 'ACTIVA';
                    const label = isActive ? 'ACTIVA' : 'INACTIVA';
                    const cls = isActive ? 'badge badge-active' : 'badge badge-inactive';
                    return `<span class="${cls}">${label}</span>`;
                }
            },
            {
                data: 'created_at', title: 'Fecha Registro', render: function (data) {
                    if (!data) return '';
                    const d = new Date(data);
                    if (isNaN(d)) return '';
                    const yyyy = d.getFullYear();
                    const mm = String(d.getMonth() + 1).padStart(2, '0');
                    const dd = String(d.getDate()).padStart(2, '0');
                    return `${yyyy}-${mm}-${dd}`;
                }
            },
            {
                data: null, title: 'Acciones', orderable: false, searchable: false, render: function (data, type, row) {
                    const idAttr = row.idEncuesta ? `data-id="${row.idEncuesta}"` : '';
                    return `
                    <div class="actions">
                        <a href="/encuestas/ver/${row.idEncuesta}" class="btn btn-info btn-ver" ${idAttr}>Ver</a>
                        <a href="/encuestas/editarEncuesta/${row.idEncuesta}" class="btn btn-primary btn-editar" ${idAttr}>Editar</a>
                        <a href="/encuestas/eliminar/${row.idEncuesta}" class="btn btn-danger btn-eliminar" ${idAttr}>Eliminar</a>
                    </div>
                `;
                }
            }
        ],

        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        }
    });
});