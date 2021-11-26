<?php require_once APPROOT . '/Views/Inc/header.php'; ?>
<div class="container">
    <div class="row m-3">
        <div class="col-md-5 mx-auto shadow-sm rounded-3 p-4 formulario">
            <div class="alert alert-warning p-2" role="alert">
                Alerta de éxito.
            </div>
            <div class="alert alert-danger p-2" role="alert">
                Alerta de validación.
            </div>
            <div class="alert alert-info p-2" role="alert">
                Alerta de informativa.
            </div>
            <div class="titulo fw-bold mb-2">Iniciar sesión</div>
            <form class="row g-3">
                <div class="col-12">
                    <label class="form-label">Usuario ó correo electrónico</label>
                    <input type="text" name="usuarioCorreo" class="form-control form-control-sm">
                </div>
                <div class="col-12">
                    <label class="form-label">Contraseña</label>
                    <input type="text" name="clave" class="form-control form-control-sm">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success btn-sm">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once APPROOT . '/Views/Inc/footer.php'; ?>