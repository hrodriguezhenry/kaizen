<?php require_once 'config.php'; ?>
<?php require_once 'conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Kaizen</title>
    <link rel="stylesheet" href="<?= SERVERURL; ?>/css/bootstrap.min.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?= SERVERURL; ?>">Kaizen</a>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Usuarios
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?= SERVERURL; ?>/usuarios/crear.php">Crear Usuario</a></li>
                            <li><a class="dropdown-item" href="<?= SERVERURL; ?>/usuarios/leer.php">Listar Usuarios</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Ejercicio
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= SERVERURL; ?>/exercise/create.php">Crear Ejercicio</a></li>
                            <li><a class="dropdown-item" href="<?= SERVERURL; ?>/exercise/read.php">Listar Ejercicios</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Hábito
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= SERVERURL; ?>/habit/create.php">Crear Hábito</a></li>
                            <li><a class="dropdown-item" href="<?= SERVERURL; ?>/habit/read.php">Listar Hábitos</a></li>
                        </ul>
                    </li>
                </ul>
                </div>
            </div>
        </nav>
    </header>