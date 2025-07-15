<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Intranet OSTTECH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="/public/css/styles.css">
    <link rel="stylesheet" href="/public/css/styles_osttech.css?v=2">
    <link rel="stylesheet" href="/public/css/print.css" media="print">
    <link rel="stylesheet" href="/public/css/styles.css" media="print">
    <link rel="stylesheet" href="/public/css/styles_osttech.css?v=2" media="print">
    <link rel="stylesheet" href="/public/css/calendario.css">
    <link rel="stylesheet" href="/public/css/pagination.css">

    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet"/>

    <!--CSS Quill editor-->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

    <!-- Carga de jQuery antes de cualquier script que lo use -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 

    <!-- Bootstrap Bundle (incluye Popper.js, necesario para tooltips, dropdowns, alerts dismiss...) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

    <!-- Cropper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js" defer></script>

    <!-- Quill Editor JS -->
     <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

    <?php if (empty($_SESSION['tablet_mode'])): ?>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const isTablet = window.matchMedia("(pointer: coarse)").matches;
            const isPortrait = window.matchMedia("(orientation: portrait)").matches;
            const pathname = window.location.pathname;
            const yaDentro = pathname.includes('/registro_horario') || pathname.includes('/dashboard');

            if (isTablet && isPortrait && !yaDentro) {
                window.location.href = "/login_tablet";
            }
        });
    </script>
    <?php endif; ?>

</head>

<body class="bg-light">
