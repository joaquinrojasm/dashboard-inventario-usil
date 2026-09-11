<?php

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';

$message = '';
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $sku = strtoupper(trim($_POST['sku'] ?? ''));
    $categoria = trim($_POST['categoria'] ?? '');
    $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
    $precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);

    if ($nombre === '' || $sku === '' || $categoria === '' || $stock === false || $stock < 0 || $precio === false || $precio < 0) {
        $message = 'Completa correctamente todos los campos.';
        $messageType = 'danger';
    } else {
        try {
            $statement = $pdo->prepare(
                'INSERT INTO productos (nombre, sku, categoria, stock, precio)
                 VALUES (:nombre, :sku, :categoria, :stock, :precio)'
            );
            $statement->execute([
                'nombre' => $nombre,
                'sku' => $sku,
                'categoria' => $categoria,
                'stock' => $stock,
                'precio' => $precio,
            ]);
            $message = 'Producto registrado correctamente.';
        } catch (PDOException $error) {
            $message = $error->getCode() === '23000'
                ? 'El SKU ya existe. Usa un código diferente.'
                : 'No se pudo guardar el producto.';
            $messageType = 'danger';
        }
    }
}

$products = $pdo->query(
    'SELECT id, nombre, sku, categoria, stock, precio
     FROM productos ORDER BY id DESC'
)->fetchAll();

$totalUnits = array_sum(array_column($products, 'stock'));
$lowStock = count(array_filter($products, static fn ($product) => $product['stock'] > 0 && $product['stock'] <= 12));
$inventoryValue = array_reduce($products, static fn ($total, $product) => $total + ($product['stock'] * $product['precio']), 0);
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Bodega Clara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar bg-dark navbar-dark">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Bodega Clara</span>
            <a class="btn btn-outline-light btn-sm" href="logout.php">Cerrar sesión</a>
        </div>
    </nav>
    <main class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <p class="text-uppercase text-secondary small fw-semibold mb-1">Panel de inventario</p>
                <h1 class="h3 mb-1">Resumen de productos</h1>
                <p class="text-secondary mb-0">Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?>.</p>
            </div>
        </div>

        <?php if ($message !== ''): ?>
            <div class="alert alert-<?= $messageType ?>" role="alert"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3"><div class="card border-0 shadow-sm"><div class="card-body"><p class="text-secondary mb-1">Productos</p><strong class="fs-3"><?= count($products) ?></strong></div></div></div>
            <div class="col-sm-6 col-lg-3"><div class="card border-0 shadow-sm"><div class="card-body"><p class="text-secondary mb-1">Unidades</p><strong class="fs-3"><?= $totalUnits ?></strong></div></div></div>
            <div class="col-sm-6 col-lg-3"><div class="card border-0 shadow-sm"><div class="card-body"><p class="text-secondary mb-1">Stock bajo</p><strong class="fs-3 text-warning"><?= $lowStock ?></strong></div></div></div>
            <div class="col-sm-6 col-lg-3"><div class="card border-0 shadow-sm"><div class="card-body"><p class="text-secondary mb-1">Valor total</p><strong class="fs-3">$<?= number_format($inventoryValue, 2) ?></strong></div></div></div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <section class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="h5">Productos registrados</h2>
                        <p class="text-secondary small">Datos consultados directamente desde MySQL.</p>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead><tr><th>Producto</th><th>SKU</th><th>Categoría</th><th>Stock</th><th>Precio</th></tr></thead>
                                <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($product['nombre']) ?></td>
                                        <td><?= htmlspecialchars($product['sku']) ?></td>
                                        <td><?= htmlspecialchars($product['categoria']) ?></td>
                                        <td><?= (int) $product['stock'] ?></td>
                                        <td>$<?= number_format((float) $product['precio'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-lg-4">
                <section class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="h5">Registrar producto</h2>
                        <p class="text-secondary small">El registro se guardará en MySQL.</p>
                        <form method="post">
                            <div class="mb-3"><label for="nombre" class="form-label">Nombre</label><input id="nombre" name="nombre" class="form-control" required></div>
                            <div class="mb-3"><label for="sku" class="form-label">SKU</label><input id="sku" name="sku" class="form-control" required></div>
                            <div class="mb-3"><label for="categoria" class="form-label">Categoría</label><select id="categoria" name="categoria" class="form-select" required><option value="">Selecciona una opción</option><option>Alimentos</option><option>Lácteos</option><option>Higiene</option><option>Bebidas</option></select></div>
                            <div class="row g-2 mb-3"><div class="col-6"><label for="stock" class="form-label">Stock</label><input id="stock" name="stock" type="number" min="0" class="form-control" required></div><div class="col-6"><label for="precio" class="form-label">Precio</label><input id="precio" name="precio" type="number" min="0" step="0.01" class="form-control" required></div></div>
                            <button class="btn btn-primary w-100" type="submit">Guardar producto</button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>