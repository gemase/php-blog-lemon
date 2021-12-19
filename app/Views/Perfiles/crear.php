<?php require_once APPROOT . '/Views/Inc/header.php'; ?>
<div class="container">
    <div class="row m-3">
        <div class="col-md-5 mx-auto shadow-sm rounded-3 p-4 formulario">
            <div id="contAlertaCrearPerfil"></div>
            <div class="row mb-2">
                <div class="col">
                    <div class="titulo fw-bold">Crear perfil</div>
                </div>
                <div class="col-md-auto">
                    <a class="btn btn-primary btn-sm" href="<?=URLROOT?>/perfiles/">Catálogo</a>
                </div>
            </div>
            <form id="formCrearPerfil" class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control form-control-sm">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Estatus</label>
                    <select name="estatus" class="form-select form-select-sm">
                        <option value="" selected>Seleccione...</option>
                        <?php foreach ($aEstatus as $key => $value): ?>
                            <option value="<?=$key?>"><?=$value?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Catálogo de usuarios</label>
                    <select name="catalogoUsuarios" class="form-select form-select-sm">
                        <option value="" selected>Seleccione...</option>
                        <?php foreach ($aPermisos as $key => $value): ?>
                            <option value="<?=$key?>"><?=$value?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Catálogo de perfiles</label>
                    <select name="catalogoPerfiles" class="form-select form-select-sm">
                        <option value="" selected>Seleccione...</option>
                        <?php foreach ($aPermisos as $key => $value): ?>
                            <option value="<?=$key?>"><?=$value?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Catálogo de artículos</label>
                    <select name="catalogoArticulos" class="form-select form-select-sm">
                        <option value="" selected>Seleccione...</option>
                        <?php foreach ($aPermisos as $key => $value): ?>
                            <option value="<?=$key?>"><?=$value?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Catálogo de categorías</label>
                    <select name="catalogoCategorias" class="form-select form-select-sm">
                        <option value="" selected>Seleccione...</option>
                        <?php foreach ($aPermisos as $key => $value): ?>
                            <option value="<?=$key?>"><?=$value?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="text-center d-grid gap-2">
                    <button id="botonCrearPerfil" type="submit" class="btn btn-primary btn-sm">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (e) => {
        Perfil.eventosCrearPerfil()
    })
</script>

<?php require_once APPROOT . '/Views/Inc/footer.php'; ?>