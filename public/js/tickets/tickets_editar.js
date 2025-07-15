document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formEditarTicket");
  const mensaje = document.getElementById("mensaje");

  // Mostrar mensaje dinámico
  function mostrarMensaje(texto, tipo = "info") {
    mensaje.textContent = texto;
    mensaje.className = `alert alert-${tipo} mt-3`;
    mensaje.classList.remove("d-none");

    setTimeout(() => mensaje.classList.add("opacity-0"), 2500);
    setTimeout(() => {
      mensaje.classList.add("d-none");
      mensaje.classList.remove("opacity-0");
    }, 3000);
  }

  // Enviar formulario
  form?.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    try {
      const response = await fetch("/api/tickets/update", {
        method: "POST",
        body: formData,
      });
      const data = await response.json();

      if (data.success) {
        mostrarMensaje("Cambios guardados con éxito", "success");
      } else {
        mostrarMensaje(data.error || "Error al guardar los cambios", "danger");
      }
    } catch (err) {
      console.error("Error:", err);
      mostrarMensaje("Error en la petición", "danger");
    }
  });

  // Actualizar estado del ticket
  function actualizarEstado(nuevoEstado) {
    const ticketId = form.querySelector('input[name="id"]').value;

    fetch("/api/tickets/cambiar_estado", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: ticketId, estado: nuevoEstado }),
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) location.reload();
        else mostrarMensaje("No se pudo actualizar el estado", "danger");
      })
      .catch(err => {
        console.error(err);
        mostrarMensaje("Error al cambiar estado", "danger");
      });
  }

  // Botones de estado
  document.getElementById("botonFinalizarTicket")?.addEventListener("click", () => actualizarEstado("finalizado"));
  document.getElementById("botonAlbaranarTicket")?.addEventListener("click", () => actualizarEstado("albaranado"));
  document.getElementById("botonPendienteTicket")?.addEventListener("click", () => actualizarEstado("pendiente"));

  // Mostrar campos según tipo de asignación
  document.querySelectorAll('input[name="tipo_asignacion"]').forEach(radio => {
    radio.addEventListener("change", () => {
      document.getElementById("select_departamentos").classList.toggle("d-none", radio.value !== "departamento");
      document.getElementById("select_tecnicos").classList.toggle("d-none", radio.value !== "tecnico");
    });
  });

  // Editar comentario
  document.querySelectorAll(".btn-editar-comentario").forEach(btn => {
    btn.addEventListener("click", () => {
      const item = btn.closest("li");
      const contenidoHTML = item.querySelector(".comentario-contenido").innerHTML.trim();
      const textarea = item.querySelector(".comentario-editor");

      const esSistema = contenidoHTML.includes("Se ha iniciado un comentario");
      textarea.value = esSistema ? "" : contenidoHTML.replace(/<br\s*\/?>/gi, "\n");

      item.querySelector(".comentario-contenido").classList.add("d-none");
      textarea.classList.remove("d-none");
      item.querySelector(".comentario-acciones").classList.remove("d-none");
    });
  });

  // Cancelar edición de comentario
  document.querySelectorAll(".cancelar-edicion").forEach(btn => {
    btn.addEventListener("click", () => {
      const item = btn.closest("li");
      item.querySelector(".comentario-contenido").classList.remove("d-none");
      item.querySelector(".comentario-editor").classList.add("d-none");
      item.querySelector(".comentario-acciones").classList.add("d-none");
    });
  });

  // Confirmar edición y abrir modal
  document.querySelectorAll(".guardar-edicion").forEach(btn => {
    btn.addEventListener("click", () => {
      const item = btn.closest("li");
      const id = item.dataset.id;
      const contenido = item.querySelector(".comentario-editor").value.trim();

      if (!contenido) return alert("El comentario no puede estar vacío");

      const modal = document.getElementById("modalFinalizarComentario");
      modal.dataset.id = id;
      modal.dataset.contenido = contenido;

      new bootstrap.Modal(modal).show();
    });
  });

  // Mostrar input de hora manual
  document.getElementById("introducirHora")?.addEventListener("change", () => {
    document.getElementById("horaManualInput")?.classList.remove("d-none");
  });

  document.getElementById("usarHoraActual")?.addEventListener("change", () => {
    document.getElementById("horaManualInput")?.classList.add("d-none");
  });

  // Confirmar guardar comentario con hora
  document.getElementById("confirmarFinalizarComentario")?.addEventListener("click", async () => {
    const modal = document.getElementById("modalFinalizarComentario");
    const id = modal.dataset.id;
    const contenido = modal.dataset.contenido;
    const usarActual = document.getElementById("usarHoraActual").checked;
    const horaManual = document.getElementById("horaManualInput").value;

    let hora_fin = "";

    if (usarActual) {
      const ahora = new Date();
      hora_fin = ahora.toTimeString().slice(0, 8);
    } else if (horaManual) {
      hora_fin = horaManual + ":00";
    } else {
      return alert("Debes introducir una hora válida.");
    }

    const formData = new FormData();
    formData.append("id", id);
    formData.append("contenido", contenido);
    formData.append("hora_fin", hora_fin);

    try {
      const res = await fetch("/update_comentarios", {
        method: "POST",
        body: formData,
      });

      const result = await res.json();

      if (result.success) {
        const item = document.querySelector(`li[data-id="${id}"]`);
        item.querySelector(".comentario-contenido").innerHTML = contenido.replace(/\n/g, "<br>");
        item.querySelector(".comentario-contenido").classList.remove("d-none");
        item.querySelector(".comentario-editor").classList.add("d-none");
        item.querySelector(".comentario-acciones").classList.add("d-none");
        bootstrap.Modal.getInstance(modal)?.hide();
      } else {
        alert("Error al actualizar el comentario");
      }
    } catch (error) {
      console.error("Error:", error);
      alert("Error en la petición");
    }
  });
});
