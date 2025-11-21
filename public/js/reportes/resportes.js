$(function () {
  var $encuesta = $('#encuesta');
  var $periodo = $('#periodo');
  var $result = $('#resultadosContainer');
  var $titulo = $('#tituloResultados');
  $('#btnPdf').prop('disabled', false);

  $.get('/reportes/encuestas', function (items) {
    var opts = '<option value="">Seleccione una encuesta</option>';
    (items || []).forEach(function (it) {
      opts += '<option value="' + it.idEncuesta + '">' + escapeHtml(it.nombre || '') + '</option>';
    });
    $encuesta.html(opts);
  });

  $('#btnGenerar').on('click', function () {
    var id = $encuesta.val();
    var per = $periodo.val();
    if (!id) { alert('Seleccione una encuesta'); return; }
    $result.html('<div class="loading">Generando...</div>');
    $.get('/reportes/generar', { encuesta: id, periodo: per }, function (data) {
      renderReporte(data);
    }).fail(function () {
      $result.html('<div class="error">No se pudo generar el reporte</div>');
    });
  });

  $('#btnExcel').on('click', function () {
    var id = $encuesta.val();
    var per = $periodo.val();
    if (!id) { alert('Seleccione una encuesta'); return; }
    window.location.href = '/reportes/excel?encuesta=' + encodeURIComponent(id) + '&periodo=' + encodeURIComponent(per || '');
  });

  $('#btnPdf').on('click', function () {
    var id = $encuesta.val();
    var per = $periodo.val();
    if (!id) { alert('Seleccione una encuesta'); return; }
    window.location.href = '/reportes/pdf?encuesta=' + encodeURIComponent(id) + '&periodo=' + encodeURIComponent(per || '');
  });

  function renderReporte(data) {
    if (!data || !data.encuesta) { $result.html(''); return; }
    $titulo.text('Resultados: ' + (data.encuesta.nombre || ''));
    var periodoText = '';
    if (data.filtros && data.filtros.desde && data.filtros.hasta) {
      periodoText = 'Período: ' + formatDate(data.filtros.desde) + ' - ' + formatDate(data.filtros.hasta);
    }
    var html = '';
    html += '<p style="color:#7f8c8d;margin-bottom:20px;">' + periodoText + ' | Total de respuestas: ' + (data.totalRespuestas || 0) + '</p>';
    html += '<div class="estadistica">';
    html += statBox('Total Respuestas', data.totalRespuestas || 0);
    var prom = data.promedioSatisfaccion != null ? (data.promedioSatisfaccion + '/5') : 'N/D';
    html += statBox('Promedio Satisfacción', prom);
    html += statBox('Tasa de Respuesta', 'N/D');
    html += statBox('Tiempo Promedio', 'N/D');
    html += '</div>';

    (data.preguntas || []).forEach(function (p, idx) {
      html += '<div class="resultado-item">';
      html += '<h4>' + (idx + 1) + '. ' + escapeHtml(p.texto || '') + '</h4>';
      var tipoLabel = tipoToLabel(p.tipo);
      html += '<p style="color:#7f8c8d;margin-top:5px;">Tipo: ' + tipoLabel + '</p>';

      if (p.tipo === 'opcion-multiple') {
        (p.resumen.opciones || []).forEach(function (o) {
          html += barraItem(escapeHtml(o.texto || ''), o.cantidad, o.porcentaje, '#3498db');
        });
      } else if (p.tipo === 'si-no') {
        html += barraItem('Sí', p.resumen.si || 0, p.resumen.porcentajeSi || 0, '#27ae60');
        html += barraItem('No', p.resumen.no || 0, p.resumen.porcentajeNo || 0, '#e74c3c');
      } else if (p.tipo === 'escala') {
        (p.resumen.distribucion || []).forEach(function (d) {
          html += barraItem('Valor ' + d.valor, d.cantidad || 0, d.porcentaje || 0, '#8e44ad');
        });
      } else {
        html += '<div style="margin-top:15px;background:#f8f9fa;padding:15px;border-radius:5px;">';
        html += '<p><strong>Respuestas recientes:</strong></p>';
        html += '<ul style="margin-top:10px;margin-left:20px;">';
        (p.resumen.recientes || []).forEach(function (t) {
          html += '<li>' + escapeHtml(t || '') + '</li>';
        });
        html += '</ul>';
        html += '</div>';
      }
      html += '</div>';
    });

    $result.html(html);
  }

  function statBox(t, v) {
    return '<div class="stat-box"><h4>' + t + '</h4><div class="numero">' + v + '</div></div>';
  }

  function barraItem(label, cantidad, porcentaje, color) {
    var pct = Math.max(0, Math.min(100, Number(porcentaje) || 0));
    var style = 'width:' + pct + '%;background:' + (color || '#3498db') + ';';
    var html = '';
    html += '<div style="margin-top:15px;">';
    html += '<div style="display:flex;justify-content:space-between;margin-bottom:5px;">';
    html += '<span>' + label + '</span>';
    html += '<span>' + (cantidad || 0) + ' respuestas (' + pct + '%)</span>';
    html += '</div>';
    html += '<div class="barra-progreso">';
    html += '<div class="barra-fill" style="' + style + '">' + pct + '%</div>';
    html += '</div>';
    html += '</div>';
    return html;
  }

  function tipoToLabel(tipo) {
    if (tipo === 'opcion-multiple') return 'Opción múltiple';
    if (tipo === 'si-no') return 'Sí/No';
    if (tipo === 'escala') return 'Escala 1-5';
    return 'Texto';
  }

  function escapeHtml(s) {
    return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
  }

  function formatDate(s) {
    return String(s || '').split(' ')[0];
  }
});