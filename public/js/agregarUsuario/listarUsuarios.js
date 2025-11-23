$(document).ready(function () {
    $('#usuariosTable').DataTable({
        ajax: {
            url: '/usuarios/listar',
            type: 'GET',
            dataSrc: ''
        },
        columns: [
            { data: 'id', title: 'ID' },
            { data: 'nombre', title: 'Nombre' },
            { data: 'email', title: 'Correo Electrónico' },
            { data: 'rol', title: 'Rol' },
            {
                data: 'estado', title: 'Estado', render: function (data) {
                    const isActive = String(data || '').toUpperCase() === 'ACTIVO';
                    const label = isActive ? 'Activo' : 'Inactivo';
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
                    const isActive = String(row.estado || '').toUpperCase() === 'ACTIVO';
                    const toggleText = isActive ? 'Desactivar' : 'Activar';
                    const toggleClass = isActive ? 'btn-secondary' : 'btn-success';
                    const idAttr = row.id ? `data-id="${row.id}"` : '';
                    return `
                    <div class="actions">
                        <a href="/usuarios/editar/${row.id}" class="btn btn-primary btn-editar" ${idAttr}>Editar</a>
                        <a href="/usuarios/eliminar/${row.id}" class="btn ${toggleClass} btn-toggle" ${idAttr} data-estado="${row.estado}">${toggleText}</a>
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
