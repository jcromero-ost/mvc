document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("form-editar-ticket");

  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    try {
      const response = await fetch("/controllers/editar_ticket_ajax.php?action=actualizar", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        alert("Ticket actualizado correctamente.");
        location.reload();
      } else {
        alert("Error al actualizar el ticket: " + (result.message || "Error desconocido"));
      }
    } catch (error) {
      console.error("Error al enviar el formulario:", error);
      alert("Ocurrió un error al procesar la solicitud.");
    }
  });
});
