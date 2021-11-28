<?php require_once APPROOT . '/Views/Inc/header.php'; ?>
<div class="container">
    <div class="row m-3">
        <div class="col-md-6 mx-auto shadow-sm rounded-3 p-4 formulario">
            <div id="contAlertaNuevoUsuario"></div>
            <div class="titulo fw-bold mb-2">Crear cuenta</div>
            <form id="formNuevoUsuario" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control form-control-sm">
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-weight: 500 !important;">Apellido</label>
                    <input type="text" name="apellido" class="form-control form-control-sm">
                </div>
                <div class="col-12">
                    <label class="form-label">Usuario</label>
                    <input type="text" name="usuario" class="form-control form-control-sm">
                </div>
                <div class="col-12">
                    <label class="form-label">Correo electrónico</label>
                    <input type="text" name="correo" class="form-control form-control-sm">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="clave" class="form-control form-control-sm">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Vuelve a escribir la contraseña</label>
                    <input type="password" name="claveConfirmacion" class="form-control form-control-sm">
                </div>
                <div class="text-center">
                    <button id="botonNuevoUsuario" type="submit" class="btn btn-primary btn-sm">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (e) => {
        Usuario.eventosNuevoUsuario()
    })
</script>

<?php require_once APPROOT . '/Views/Inc/footer.php'; ?>