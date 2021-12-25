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
                <form class="row gx-3 gy-2 align-items-center" action="<?=URLROOT?>/usuarios/listar" method="post">
                    <div class="col-md-4">
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text" title="Fecha de registro (Del)">Fecha inicial</span>
                            <input type="date" name="fechaInicial" value="<?=$filtroFechaInicial?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text" title="Fecha de registro (Al)">Fecha final</span>
                            <input type="date" name="fechaFinal" value="<?=$filtroFechaFinal?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm mb-3">
                            <label class="input-group-text">Estatus</label>
                            <select class="form-select" name="estatus">
                                <option value="" selected>Seleccione...</option>
                                <?php foreach ($aEstatus as $key => $value): 
                                    $seleccionado = $key == $filtroEstatus ? 'selected' : ''; ?>
                                    <option <?=$seleccionado?> value="<?=$key?>"><?=$value?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm mb-3">
                            <label class="input-group-text">Perfil</label>
                            <select class="form-select" name="idPerfil">
                                <option value="" selected>Seleccione...</option>
                                <?php foreach ($colPerfiles as $key => $_Perfil): 
                                    $seleccionado = $key == $filtroIdPerfil ? 'selected' : ''; ?>
                                    <option <?=$seleccionado?> value="<?=$key?>"><?=$_Perfil->getNombre()?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text" title="Buscar por nombre, apellido, usuario ó correo electrónico">Buscar</span>
                            <input type="text" name="buscar" value="<?=$filtroBuscar?>" class="form-control">
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
                                    <?php if ($_Usuario->esProtegido()): ?>
                                        <a class="btn badge rounded-pill bg-info">Protegido</a>
                                    <?php else: ?>
                                        <?php if ($tienePermisoEdicion): ?>
                                            <a href="<?=URLROOT?>/usuarios/actualizar/<?=$_Usuario->getId()?>" class="btn badge rounded-pill bg-success">
                                                Editar
                                            </a>
                                        <?php endif; ?>
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