<?php require_once APPROOT . '/Views/Inc/header.php'; ?>
<div class="container">
    <div class="row m-3">
        <div class="col-md-5 mx-auto border rounded-2 p-4 formulario">
            <div class="alert alert-warning p-2" role="alert">
                Alerta de éxito.
            </div>
            <div class="alert alert-danger p-2" role="alert">
                Alerta de validación.
            </div>
            <div class="alert alert-info p-2" role="alert">
                Alerta de informativa.
            </div>
            <div class="titulo fw-bold mb-2">Crear cuenta</div>
            <form class="row g-3">
                <div class="col-12">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control form-control-sm">
                </div>
                <div class="col-12">
                    <label class="form-label">Apellido</label>
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
                <div class="col-12">
                    <label class="form-label">Contraseña</label>
                    <input type="text" name="clave" class="form-control form-control-sm">
                </div>
                <div class="col-12">
                    <label class="form-label">Vuelve a escribir la contraseña</label>
                    <input type="text" name="claveConfirmacion" class="form-control form-control-sm">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-sm">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once APPROOT . '/Views/Inc/footer.php'; ?>