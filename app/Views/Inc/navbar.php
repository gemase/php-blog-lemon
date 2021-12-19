<nav class="navbar navbar-expand-lg navbar-light shadow-sm navbar-principal">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?=URLROOT?>">Blog Lemon</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if (isAutenticado()):
                    $_SysUsuario = getInsSysUsuario();
                    $perSoloLecPerfiles = $_SysUsuario->getInsPerfil()->tienePermiso('c_perfiles', \App\Models\Perfil::P_LEC);
                    $perSoloLecPerfiles = $perSoloLecPerfiles !== true ? false : true;
                    $perSoloLecUsuarios = $_SysUsuario->getInsPerfil()->tienePermiso('c_usuarios', \App\Models\Perfil::P_LEC);
                    $perSoloLecUsuarios = $perSoloLecUsuarios !== true ? false : true;
                    $perSoloLecArticulos = $_SysUsuario->getInsPerfil()->tienePermiso('c_articulos', \App\Models\Perfil::P_LEC);
                    $perSoloLecArticulos = $perSoloLecArticulos !== true ? false : true;
                    $perSoloLecCategorias = $_SysUsuario->getInsPerfil()->tienePermiso('c_categorias', \App\Models\Perfil::P_LEC);
                    $perSoloLecCategorias = $perSoloLecCategorias !== true ? false : true;
                    ?>
                    <?php if ($perSoloLecPerfiles || $perSoloLecUsuarios || $perSoloLecArticulos || $perSoloLecCategorias): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Catálogos
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <?php if ($perSoloLecPerfiles): ?>
                                    <li><a class="dropdown-item" href="<?=URLROOT?>/perfiles">Perfiles</a></li>
                                <?php endif; ?>

                                <?php if ($perSoloLecUsuarios): ?>
                                    <li><a class="dropdown-item" href="<?=URLROOT?>/usuarios/listar"">Usuarios</a></li>
                                <?php endif; ?>

                                <?php if ($perSoloLecArticulos): ?>
                                    <li><a class="dropdown-item" href="#">Artículos</a></li>
                                <?php endif; ?>

                                <?php if ($perSoloLecCategorias): ?>
                                    <li><a class="dropdown-item" href="#">Categorías</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?=$_SysUsuario->getNombre()?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?=URLROOT?>/usuarios/<?=$_SysUsuario->getUsuario()?>">Perfil</a></li>
                            <li><a class="dropdown-item" href="<?=URLROOT?>/usuarios/logout">Cerrar sesión</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=URLROOT?>/usuarios/login">Iniciar sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=URLROOT?>/usuarios/registro">Crear cuenta</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>