document.addEventListener('DOMContentLoaded', function () {

    $.get('/api/dashboard/statistics', function (resp) {
        var counts = resp && resp.counts ? resp.counts : {};
        var nums = $('.stat-card .stat-number');
        if (nums.length >= 4) {
            nums.eq(0).text(counts.encuestas_activas || 0);
            nums.eq(1).text(counts.respuestas_recibidas || 0);
            nums.eq(2).text(counts.usuarios_autorizados || 0);
            nums.eq(3).text(counts.encuestas_totales || 0);
        }

    });

    new DataTable('#recentTable', {
        ajax: {
            url: '/api/dashboard/statistics',
            type: 'GET',
            dataSrc: 'recientes'
        },
        columns: [
            { data: 'nombre', title: 'Nombre' },
            { data: 'created_at', title: 'Fecha Creación', render: function (data) {
                if (!data) return '';
                const d = new Date(data);
                if (isNaN(d)) return String(data).split(' ')[0];
                const yyyy = d.getFullYear();
                const mm = String(d.getMonth() + 1).padStart(2, '0');
                const dd = String(d.getDate()).padStart(2, '0');
                return `${yyyy}-${mm}-${dd}`;
            } },
            { data: 'respuestas', title: 'Respuestas' },
            {
                data: 'estado', title: 'Estado', render: function (data) {
                    const isActive = String(data || '').toUpperCase() === 'ACTIVA';
                    const label = isActive ? 'ACTIVA' : 'INACTIVA';
                    const cls = isActive ? 'badge badge-active' : 'badge badge-inactive';
                    return `<span class="${cls}">${label}</span>`;
                }
            },
            {
                data: null, title: 'Acciones', orderable: false, searchable: false, render: function (data, type, row) {
                    var idAttr = row.idEncuesta ? ('data-id="' + row.idEncuesta + '"') : '';
                    return '<div class="actions">'
                        + '<a href="/encuestas/ver/' + row.idEncuesta + '" class="btn btn-info btn-ver" ' + idAttr + '>Ver</a>'
                        + '<a href="/reportes" class="btn btn-success" ' + idAttr + '>Reporte</a>'
                        + '</div>';
                }
            }
        ],

        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        }
    });
});