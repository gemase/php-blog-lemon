<?php require_once APPROOT . '/Views/Inc/header.php'; ?>
<div class="container">
    <div class="row m-3">
        <div class="col-md-12 mx-auto shadow-sm rounded-3 p-4 formulario">

            <?php if (!empty($msgValidacion)):
                muestraAlerta($msgValidacion);
            endif; ?>

            <div class="row mb-3">
                <div class="col">
                    <div class="titulo fw-bold">Catálogo de perfiles</div>
                </div>
                <div class="col-md-auto">
                    <a class="btn btn-primary btn-sm">Crear</a>
                </div>
            </div>

            <div class="mb-3">
                <form class="row gx-3 gy-2 align-items-center">
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
                            <th scope="col">Creado el</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Estatus</th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($colPerfiles as $_Perfil): ?>
                            <tr>
                                <th scope="row"><?=$_Perfil->getFechaCreo()?></th>
                                <td><?=$_Perfil->getNombre()?></td>
                                <td><?=$_Perfil->getEstatus(true)?></td>
                                <td class="text-center">
                                    <a href="#" class="btn badge rounded-pill bg-success">
                                        Ver
                                    </a>
                                    <a href="#" class="btn badge rounded-pill bg-success">
                                        Editar
                                    </a>
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