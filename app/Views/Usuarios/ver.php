<?php require_once APPROOT . '/Views/Inc/header.php'; ?>
<div class="container">
    <div class="row m-3">
        <div class="col-md-5 mx-auto shadow-sm rounded-3 p-4 formulario">
            <div class="row mb-3">
                <div class="col">
                    <div class="titulo fw-bold">Usuario</div>
                </div>
                <div class="col-md-auto">
                    <?php if ($tienePermisoEdicion && !$_Usuario->esProtegido()): ?>
                        <a class="btn btn-primary btn-sm" href="<?=URLROOT?>/usuarios/actualizar/<?=$_Usuario->getId()?>">Editar</a>
                    <?php endif; ?>
                    <a class="btn btn-primary btn-sm" href="<?=URLROOT?>/usuarios/listar">Catálogo</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="mb-1">Usuario</div>
                    <div class="text-muted"><?=$_Usuario->getUsuario()?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="mb-1">Correo electrónico</div>
                    <div class="text-muted"><?=$_Usuario->getCorreo()?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="mb-1">Perfil</div>
                    <div class="text-muted"><?=$_Usuario->getInsPerfil()->getNombre()?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="mb-1">Estatus</div>
                    <div class="text-muted"><?=$_Usuario->getEstatus(true)?></div>
                </div>
                <hr>
                <div class="col-md-6 mb-3">
                    <div class="mb-1">Nombre</div>
                    <div class="text-muted"><?=$_Usuario->getNombre()?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="mb-1">Apellido</div>
                    <div class="text-muted"><?=$_Usuario->getApellido()?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="mb-1">País</div>
                    <div class="text-muted"><?=$_Usuario->getPais() ?? 'N/D'?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="mb-1">Ciudad</div>
                    <div class="text-muted"><?=$_Usuario->getCiudad() ?? 'N/D'?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="mb-1">Género</div>
                    <div class="text-muted"><?=$_Usuario->getGenero(true) ?? 'N/D'?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="mb-1">Fecha de nacimiento</div>
                    <div class="text-muted"><?=$_Usuario->getFechaNacimiento() ?? 'N/D'?></div>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="mb-1">Biografía</div>
                    <div class="text-muted"><?=$_Usuario->getBiografia() ?? 'N/D'?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once APPROOT . '/Views/Inc/footer.php'; ?>