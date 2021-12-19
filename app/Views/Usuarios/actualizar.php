<?php
require_once APPROOT . '/Views/Inc/header.php'; ?>
<div class="container">
    <div class="row m-3">
        <div class="col-md-5 mx-auto shadow-sm rounded-3 p-4 formulario">
            <div id="contAlertaActualizaUsuInfGeneral"></div>
            <div id="contAlertaActualizaUsuInfCuenta"></div>
            <div id="contAlertaActualizaUsuInfClave"></div>
            <div class="row mb-2">
                <div class="col">
                    <div class="titulo fw-bold">Editar usuario</div>
                </div>
                <div class="col-md-auto">
                    <a class="btn btn-primary btn-sm" href="<?=URLROOT?>/usuarios/ver/<?=$_Usuario->getId()?>">ver</a>
                    <a class="btn btn-primary btn-sm" href="<?=URLROOT?>/usuarios/listar">Catálogo</a>
                </div>
            </div>
            <ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">General</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="cuenta-tab" data-bs-toggle="tab" data-bs-target="#cuenta" type="button" role="tab" aria-controls="cuenta" aria-selected="false">Cuenta</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="clave-tab" data-bs-toggle="tab" data-bs-target="#clave" type="button" role="tab" aria-controls="clave" aria-selected="false">Contraseña</button>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                    <form id="formActualizaUsuInfGeneral" class="row g-3">
                        <input type="hidden" name="id" value="<?=$_Usuario->getId()?>">
                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" value="<?=$_Usuario->getNombre()?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="apellido" value="<?=$_Usuario->getApellido()?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">País</label>
                            <input type="text" name="pais" value="<?=$_Usuario->getPais()?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ciudad</label>
                            <input type="text" name="ciudad" value="<?=$_Usuario->getCiudad()?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Género</label>
                            <select name="genero" class="form-select form-select-sm">
                                <option value="" selected>Seleccione...</option>
                                <?php foreach ($aGeneros as $key => $value): 
                                    $seleccionado = $key == $_Usuario->getGenero() ? 'selected' : ''; ?>
                                    <option <?=$seleccionado?> value="<?=$key?>"><?=$value?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de nacimiento</label>
                            <input name="fechaNacimiento" type="date" value="<?=$_Usuario->getFechaNacimiento()?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Biografía</label>
                            <textarea name="biografia" class="form-control" rows="3"><?=$_Usuario->getBiografia()?></textarea>
                        </div>
                        <div class="text-center d-grid gap-2">
                            <button id="botonActualizaUsuInfGeneral" type="submit" class="btn btn-primary btn-sm">Confirmar</button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="cuenta" role="tabpanel" aria-labelledby="cuenta-tab">
                    <form id="formActualizaUsuInfCuenta" class="row g-3">
                        <input type="hidden" name="id" value="<?=$_Usuario->getId()?>">
                        <div class="col-12">
                            <label class="form-label">Usuario</label>
                            <input type="text" name="usuario" value="<?=$_Usuario->getUsuario()?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Correo electrónico</label>
                            <input type="text" name="correo" value="<?=$_Usuario->getCorreo()?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Perfil</label>
                            <select name="idPerfil" class="form-select form-select-sm">
                                <option value="" selected>Seleccione...</option>
                                <?php foreach ($colPerfiles as $key => $_Perfil): 
                                    $seleccionado = $key == $_Usuario->getIdPerfil() ? 'selected' : ''; ?>
                                    <option <?=$seleccionado?> value="<?=$key?>"><?=$_Perfil->getNombre()?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Estatus</label>
                            <select name="estatus" class="form-select form-select-sm">
                                <option value="" selected>Seleccione...</option>
                                <?php foreach ($aEstatus as $key => $value): 
                                    $seleccionado = $key == $_Usuario->getEstatus() ? 'selected' : ''; ?>
                                    <option <?=$seleccionado?> value="<?=$key?>"><?=$value?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="text-center d-grid gap-2">
                            <button id="botonActualizaUsuInfCuenta" type="submit" class="btn btn-primary btn-sm">Confirmar</button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="clave" role="tabpanel" aria-labelledby="clave-tab">
                    <form id="formActualizaUsuInfClave" class="row g-3">
                        <input type="hidden" name="id" value="<?=$_Usuario->getId()?>">
                        <div class="col-12">
                            <label class="form-label">Nueva contraseña</label>
                            <input type="password" name="claveNueva" class="form-control form-control-sm">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Vuelve a escribir la nueva contraseña</label>
                            <input type="password" name="claveConfirmacion" class="form-control form-control-sm">
                        </div>
                        <div class="text-center d-grid gap-2">
                            <button id="botonActualizaUsuInfClave" type="submit" class="btn btn-primary btn-sm">Confirmar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (e) => {
        Usuario.eventosActualizaUsuInfGeneral()
        Usuario.eventosActualizaUsuInfCuenta()
        Usuario.eventosActualizaUsuInfClave()
    })
</script>

<?php require_once APPROOT . '/Views/Inc/footer.php'; ?>