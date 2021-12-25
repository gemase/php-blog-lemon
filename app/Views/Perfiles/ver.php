<?php require_once APPROOT . '/Views/Inc/header.php'; ?>
<div class="container">
    <div class="row m-3">
        <div class="col-md-5 mx-auto shadow-sm rounded-3 p-4 formulario">
            <div class="row mb-3">
                <div class="col">
                    <div class="titulo fw-bold">Perfil</div>
                </div>
                <div class="col-md-auto">
                    <?php if ($tienePermisoEdicion && !$_Perfil->esProtegido()): ?>
                        <a class="btn btn-primary btn-sm" href="<?=URLROOT?>/perfiles/editar/<?=$_Perfil->getId()?>">Editar</a>
                    <?php endif; ?>
                    <a class="btn btn-primary btn-sm" href="<?=URLROOT?>/perfiles/">Catálogo</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="mb-1">Nombre</div>
                    <div class="text-muted"><?=$_Perfil->getNombre()?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="mb-1">Estatus</div>
                    <div class="text-muted"><?=$_Perfil->getEstatus(true)?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="mb-1">Catálogo de usuarios</div>
                    <div class="text-muted"><?=$_Perfil->getPermisoUsuarios(true)?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="mb-1">Catálogo de perfiles</div>
                    <div class="text-muted"><?=$_Perfil->getPermisoPerfiles(true)?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="mb-1">Catálogo de artículos</div>
                    <div class="text-muted"><?=$_Perfil->getPermisoArticulos(true)?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="mb-1">Catálogo de categorías</div>
                    <div class="text-muted"><?=$_Perfil->getPermisoCategorias(true)?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once APPROOT . '/Views/Inc/footer.php'; ?>