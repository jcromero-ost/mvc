$(document).ready(function () {
  $('#formEditarComentario').on('submit', function (e) {
    e.preventDefault();

    const id = $('#comentario_id').val().trim();
    const contenido = $('#comentario_contenido').val().trim();
    const horaFin = $('#hora_fin_manual').val().trim(); // Campo de hora manual
    const usarHoraActual = $('#usar_hora_actual').is(':checked');

    if (!contenido) {
      alert('El contenido del comentario no puede estar vacío.');
      return;
    }

    // Si no se ha indicado hora y no se ha seleccionado usar actual, error
    if (!usarHoraActual && !horaFin) {
      alert('Debes introducir una hora o marcar "Usar hora actual".');
      return;
    }

    // Si se marca usar hora actual, obtenemos la hora local
    let hora_fin_final = horaFin;
    if (usarHoraActual) {
      const ahora = new Date();
      const hh = String(ahora.getHours()).padStart(2, '0');
      const mm = String(ahora.getMinutes()).padStart(2, '0');
      const ss = String(ahora.getSeconds()).padStart(2, '0');
      hora_fin_final = `${hh}:${mm}:${ss}`;
    }

    $.ajax({
      url: '/updateComentario',
      method: 'POST',
      data: {
        id: id,
        contenido: contenido,
        hora_fin: hora_fin_final
      },
      success: function (response) {
        let res;
        try {
          res = typeof response === 'string' ? JSON.parse(response) : response;
        } catch (e) {
          console.error('Error al parsear JSON:', e);
          alert('Error inesperado en la respuesta del servidor.');
          return;
        }

        if (res.success) {
          $('#modalEditarComentario').modal('hide');

          // Recargar comentarios
          $.post('/getComentarios', { id: TICKET_ID }, function (data) {
            if (data.success && data.comentarios) {
              renderizarComentarios(data.comentarios);
            }
          }, 'json');

          // Recargar tiempo total
          $.post('/getTiempoTotal', { id: TICKET_ID }, function (data) {
            if (data.tiempo_total) {
              $('#tiempo_total_label').text(data.tiempo_total);
            }
          }, 'json');
        } else {
          alert(res.error || 'Error al actualizar el comentario.');
        }
      },
      error: function (xhr, status, error) {
        console.error('Error en AJAX:', error);
        alert('Error al enviar la solicitud.');
      }
    });
  });
});
