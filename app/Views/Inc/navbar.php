<nav class="navbar navbar-expand-lg navbar-light shadow-sm navbar-principal">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?=URLROOT?>">Blog Lemon</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if (isAutenticado()): ?>
                    <li class="nav-item dropdown text-muted fw-bold">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Catálogos
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item text-muted fw-bold" href="#">Perfiles</a></li>
                            <li><a class="dropdown-item text-muted fw-bold" href="#">Usuarios</a></li>
                            <li><a class="dropdown-item text-muted fw-bold" href="#">Artículos</a></li>
                            <li><a class="dropdown-item text-muted fw-bold" href="#">Categorías</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown text-muted fw-bold">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?=getInsSysUsuario()->getNombre()?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item text-muted fw-bold" href="#">Perfil</a></li>
                            <li><a class="dropdown-item text-muted fw-bold" href="<?=URLROOT?>/usuarios/logout">Cerrar sesión</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item text-muted fw-bold">
                        <a class="nav-link" href="<?=URLROOT?>/usuarios/login">Iniciar sesión</a>
                    </li>
                    <li class="nav-item text-muted fw-bold">
                        <a class="nav-link" href="<?=URLROOT?>/usuarios/registro">Crear cuenta</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>