<div class="col-md-3 col-lg-2 sidebar bg-dark text-white">
    <div class="p-3">
        <h5 class="text-white">Panel</h5>
        <hr class="text-white">
        <p class="text-white">Usuario: <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong></p>
        <p class="text-white">Rol: <strong><?= htmlspecialchars($_SESSION['rol']) ?></strong></p>
        <hr class="text-white">

        <a href="panel.php" class="text-white text-decoration-none d-block mb-2 <?= basename($_SERVER['PHP_SELF']) === 'panel.php' ? 'fw-bold' : '' ?>">Inicio</a>

        <?php if ($_SESSION['rol'] === 'admin'): ?>

            <?php
            $menu_items = [
                'empleados' => 'Empleados',
                'municipalidades' => 'Municipalidades',
                'parqueos' => 'Parqueos',
                'lineas' => 'Líneas',
                'estaciones' => 'Estaciones',
                'buses' => 'Buses',
                'tramos' => 'Tramos',
                'viajes' => 'Viajes',
                'usuarios' => 'Usuarios'
            ];

            foreach ($menu_items as $id => $nombre):
                $is_open = (isset($submenu_abierto) && $submenu_abierto === $id);
            ?>
                <a class="text-white text-decoration-none d-flex justify-content-between align-items-center mb-2" data-bs-toggle="collapse" href="#submenu<?= ucfirst($id) ?>" role="button" aria-expanded="<?= $is_open ? 'true' : 'false' ?>" aria-controls="submenu<?= ucfirst($id) ?>">
                    <?= $nombre ?>
                    <span class="bi <?= $is_open ? 'bi-chevron-up' : 'bi-chevron-down' ?>"></span>
                </a>
                <div class="collapse ps-3 <?= $is_open ? 'show' : '' ?>" id="submenu<?= ucfirst($id) ?>">
                    <a href="<?= $id ?>.php" class="text-white text-decoration-none d-block mb-1 <?= basename($_SERVER['PHP_SELF']) === "$id.php" ? 'fw-bold' : '' ?>">Ver <?= $nombre ?></a>
                    <a href="registrar_<?= rtrim($id, 's') ?>.php" class="text-white text-decoration-none d-block mb-1 <?= basename($_SERVER['PHP_SELF']) === "registrar_".rtrim($id, 's').".php" ? 'fw-bold' : '' ?>">Registrar <?= rtrim($nombre, 's') ?></a>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <a href="lineas.php" class="text-white text-decoration-none d-block mb-2 <?= basename($_SERVER['PHP_SELF']) === 'lineas.php' ? 'fw-bold' : '' ?>">Ver Líneas</a>
            <a href="estaciones.php" class="text-white text-decoration-none d-block mb-2 <?= basename($_SERVER['PHP_SELF']) === 'estaciones.php' ? 'fw-bold' : '' ?>">Ver Estaciones</a>
            <a href="registrar_viaje.php" class="text-white text-decoration-none d-block mb-2 <?= basename($_SERVER['PHP_SELF']) === 'registrar_viaje.php' ? 'fw-bold' : '' ?>">Registrar Viaje</a>
            <a href="viajes.php" class="text-white text-decoration-none d-block mb-2 <?= basename($_SERVER['PHP_SELF']) === 'viajes.php' ? 'fw-bold' : '' ?>">Ver Registros de Viajes</a>
        <?php endif; ?>

        <a href="logout.php" class="text-white text-decoration-none d-block mt-3 <?= basename($_SERVER['PHP_SELF']) === 'logout.php' ? 'fw-bold' : '' ?>">Cerrar Sesión</a>
    </div>
</div>

