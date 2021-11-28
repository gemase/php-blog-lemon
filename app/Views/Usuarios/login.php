<?php require_once APPROOT . '/Views/Inc/header.php'; ?>
<div class="container">
    <div class="row m-3">
        <div class="col-md-5 mx-auto shadow-sm rounded-3 p-4 formulario">
            <div id="contAlertaLogin"></div>
            <div class="titulo fw-bold mb-2">Iniciar sesión</div>
            <form id="formLogin" class="row g-3">
                <div class="col-12">
                    <label class="form-label">Usuario ó correo electrónico</label>
                    <input type="text" name="usuarioCorreo" class="form-control form-control-sm">
                </div>
                <div class="col-12">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="clave" class="form-control form-control-sm">
                </div>
                <div class="text-center">
                    <button id="botonLogin" type="submit" class="btn btn-primary btn-sm">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (e) => {
        Usuario.eventosLogin()
    })
</script>

<?php require_once APPROOT . '/Views/Inc/footer.php'; ?>