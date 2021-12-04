<?php require_once APPROOT . '/Views/Inc/header.php'; ?>
<div class="container-fluid">
    <div class="row m-3">
        <div class="col-md-3">
            <div class="shadow-sm rounded-3 p-4 formulario m-1">
                <div class="card text-center border-0">
                    <div class="card-body">
                        <h5 class="card-title fw-bold"><?=$_Usuario->getNombre()?> <?=$_Usuario->getApellido()?></h5>
                        <div class="card-text">
                            <span>@<?=$_Usuario->getUsuario()?></span>
                        </div>
                        <?php if (isAutenticado() && getInsSysUsuario()->getUsuario() == trim($usuario)): ?>
                            <a href="<?=URLROOT?>/usuarios/editar" class="btn btn-primary btn-sm mt-3">Editar perfil</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="shadow-sm rounded-3 p-4 formulario m-1">

            </div>
        </div>
    </div>
</div>
<?php require_once APPROOT . '/Views/Inc/footer.php'; ?>