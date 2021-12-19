<?php require_once APPROOT . '/Views/Inc/header.php'; ?>
<div class="container">
    <div class="row m-3">
        <div class="col-md-12 mx-auto shadow-sm rounded-3 p-4 formulario">
            
            <?php if (!empty($msgValidacion)):
                muestraAlerta($msgValidacion);
            endif; ?>

            <div class="row mb-3">
                <div class="col">
                    <div class="titulo fw-bold">Catálogo de usuarios</div>
                </div>
                <div class="col-md-auto">
                    <?php if ($tienePermisoEdicion): ?>
                        <a class="btn btn-primary btn-sm" href="<?=URLROOT?>/usuarios/crear">Crear</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-3">
                <form class="row gx-3 gy-2 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text">Fecha inicial</span>
                            <input type="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text">Fecha final</span>
                            <input type="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm mb-3">
                            <label class="input-group-text" for="inputGroupSelect01">Estatus</label>
                            <select class="form-select" id="inputGroupSelect01">
                                <option selected>Seleccione...</option>
                                <option value="1">Activo</option>
                                <option value="2">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm mb-3">
                            <label class="input-group-text" for="inputGroupSelect01">Perfil</label>
                            <select class="form-select" id="inputGroupSelect01">
                                <option selected>Seleccione...</option>
                                <option value="1">Administrador</option>
                                <option value="2">Autor</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="input-group input-group-sm mb-3">
                            <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Registrado el</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellido</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Correo electrónico</th>
                            <th scope="col">Perfil</th>
                            <th scope="col">Estado</th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($colUsuarios as $_Usuario): ?>
                            <tr>
                                <th scope="row"><?=$_Usuario->getFechaCreo()?></th>
                                <td><?=$_Usuario->getNombre()?></td>
                                <td><?=$_Usuario->getApellido()?></td>
                                <td><?=$_Usuario->getUsuario()?></td>
                                <td><?=$_Usuario->getCorreo()?></td>
                                <td><?=$_Usuario->getInsPerfil()->getNombre()?></td>
                                <td><?=$_Usuario->getEstatus(true)?></td>
                                <td class="text-center">
                                    <a href="<?=URLROOT?>/usuarios/ver/<?=$_Usuario->getId()?>" class="btn badge rounded-pill bg-success">
                                        Ver
                                    </a>
                                    <?php if ($tienePermisoEdicion): ?>
                                        <a href="<?=URLROOT?>/usuarios/actualizar/<?=$_Usuario->getId()?>" class="btn badge rounded-pill bg-success">
                                            Editar
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($_Paginacion instanceof \App\Libraries\Paginacion) {
                $_Paginacion->cargaHtmlPaginacion();
            } ?>
        </div>
    </div>
</div>
<?php require_once APPROOT . '/Views/Inc/footer.php'; ?>