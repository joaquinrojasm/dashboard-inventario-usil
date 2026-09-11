<?php

session_start();

require_once __DIR__ . '/config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usuario === '' || $password === '') {
        $error = 'Completa todos los campos.';
    } else {
        $statement = $pdo->prepare(
            'SELECT id, nombre, usuario FROM usuarios
             WHERE usuario = :usuario AND password = SHA2(:password, 256)
             LIMIT 1'
        );
        $statement->execute([
            'usuario' => $usuario,
            'password' => $password,
        ]);
        $user = $statement->fetch();

        if ($user) {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];
            header('Location: dashboard.php');
            exit;
        }

        $error = 'Usuario o contraseña incorrectos.';
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso | Bodega Clara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
        <section class="card shadow-sm border-0" style="max-width: 420px; width: 100%;">
            <div class="card-body p-4 p-md-5">
                <p class="text-uppercase text-secondary small fw-semibold mb-2">Bodega Clara</p>
                <h1 class="h3 mb-2">Iniciar sesión</h1>
                <p class="text-secondary mb-4">Accede al panel de inventario.</p>

                <?php if ($error !== ''): ?>
                    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="post" novalidate>
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input id="usuario" name="usuario" type="text" class="form-control" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <input id="password" name="password" type="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>