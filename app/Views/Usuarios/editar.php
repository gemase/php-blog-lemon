<?php
require_once APPROOT . '/Views/Inc/header.php'; ?>
<div class="container">
    <div class="row m-3">
        <div class="col-md-6 mx-auto shadow-sm rounded-3 p-4 formulario">
            <div class="titulo fw-bold mb-2">Editar perfil</div>
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-general-tab" data-bs-toggle="pill" data-bs-target="#pills-general" type="button" role="tab" aria-controls="pills-general" aria-selected="true">General</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-cuenta-tab" data-bs-toggle="pill" data-bs-target="#pills-cuenta" type="button" role="tab" aria-controls="pills-cuenta" aria-selected="false">Cuenta</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-clave-tab" data-bs-toggle="pill" data-bs-target="#pills-clave" type="button" role="tab" aria-controls="pills-clave" aria-selected="false">Contraseña</button>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-general" role="tabpanel" aria-labelledby="pills-general-tab">
                    <div id="contAlertaEditaUsuInfGeneral"></div>
                    <form id="formEditaUsuInfGeneral" class="row g-3">
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
                        <div class="text-center">
                            <button id="botonEditaUsuInfGeneral" type="submit" class="btn btn-primary btn-sm">Confirmar</button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="pills-cuenta" role="tabpanel" aria-labelledby="pills-cuenta-tab">
                    <div id="contAlertaEditaUsuInfCuenta"></div>
                    <form id="formEditaUsuInfCuenta" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Usuario</label>
                            <input type="text" name="usuario" value="<?=$_Usuario->getUsuario()?>" class="form-control form-control-sm">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Correo electrónico</label>
                            <input type="text" name="correo" value="<?=$_Usuario->getCorreo()?>" class="form-control form-control-sm">
                        </div>
                        <div class="text-center">
                            <button id="botonEditaUsuInfCuenta" type="submit" class="btn btn-primary btn-sm">Confirmar</button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="pills-clave" role="tabpanel" aria-labelledby="pills-clave-tab">
                    <div id="contAlertaEditaUsuInfClave"></div>
                    <form id="formEditaUsuInfClave" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Contraseña actual</label>
                            <input type="password" name="claveActual" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nueva contraseña</label>
                            <input type="password" name="claveNueva" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vuelve a escribir la nueva contraseña</label>
                            <input type="password" name="claveConfirmacion" class="form-control form-control-sm">
                        </div>
                        <div class="text-center">
                            <button id="botonEditaUsuInfClave" type="submit" class="btn btn-primary btn-sm">Confirmar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (e) => {
        Usuario.eventosEditaUsuInfGeneral()
        Usuario.eventosEditaUsuInfCuenta()
        Usuario.eventosEditaUsuInfClave()
    })
</script>

<?php require_once APPROOT . '/Views/Inc/footer.php'; ?>