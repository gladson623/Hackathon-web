<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<title><?= e($pageTitle ?? 'Portal de Estágios UniALFA') ?></title>
<?php
iniciarSessao();
if (!empty($_SESSION['_flash'])) {
    $flash = $_SESSION['_flash'];
    unset($_SESSION['_flash']);
    $msg  = json_encode($flash['message'] ?? '');
    $tipo = in_array($flash['type'] ?? '', ['success', 'error', 'warning'], true) ? $flash['type'] : 'success';
    echo "<script>document.addEventListener('DOMContentLoaded',()=>Swal.fire({icon:'{$tipo}',text:{$msg},confirmButtonColor:'#5448bd'}));</script>";
}
?>
