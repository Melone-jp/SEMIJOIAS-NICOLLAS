<?php
require __DIR__ . '/config.php';
if (!isLoggedIn()) { header('Location: login.php'); exit; }

$rows = [
    ['id' => 1041207, 'cliente' => 'Maria Souza', 'valor' => 3600.00, 'status' => 'Pago', 'data' => '2026-09-20'],
    ['id' => 1041206, 'cliente' => 'Ana Paula', 'valor' => 2890.00, 'status' => 'Em análise', 'data' => '2026-09-19'],
    ['id' => 1041205, 'cliente' => 'João Mendes', 'valor' => 4250.00, 'status' => 'Pago', 'data' => '2026-09-18'],
    ['id' => 1041204, 'cliente' => 'Fernanda Lima', 'valor' => 3120.00, 'status' => 'Em trânsito', 'data' => '2026-09-17'],
    ['id' => 1041203, 'cliente' => 'Rafael Costa', 'valor' => 1980.00, 'status' => 'Pago', 'data' => '2026-09-16'],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos | Semijoias MR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <aside class="col-lg-2 sidebar text-white p-3">
            <div class="brand-wrap">
                <div class="brand-mark">MR</div>
                <div>
                    <div class="brand">SEMIJOIAS MR</div>
                    <small class="brand-subtitle">Business Intelligence</small>
                </div>
            </div>
            <nav class="nav flex-column gap-2 mt-4">
                <a class="nav-link" href="index.php">Dashboard</a>
                <a class="nav-link active" href="pedidos.php">Pedidos</a>
                <a class="nav-link" href="comissoes.php">Comissões</a>
                <a class="nav-link" href="caixa.php">Caixa</a>
                <a class="nav-link" href="usuarios.php">Usuários</a>
            </nav>
        </aside>
        <main class="col-lg-10 main-content">
            <div class="page-header">
                <div>
                    <p class="eyebrow">Pedidos</p>
                    <h1 class="page-title">Controle de pedidos</h1>
                    <div class="page-subtitle">Resumo operacional e acompanhamento de vendas</div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="user-pill"><span class="user-avatar"><?php echo strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)); ?></span><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Administrador'); ?></div>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">Sair</a>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="module-card">
                        <div class="text-muted small mb-2">Total</div>
                        <h3 class="mb-0">1.248</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="module-card">
                        <div class="text-muted small mb-2">Pagos</div>
                        <h3 class="mb-0">942</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="module-card">
                        <div class="text-muted small mb-2">Em análise</div>
                        <h3 class="mb-0">186</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="module-card">
                        <div class="text-muted small mb-2">Em trânsito</div>
                        <h3 class="mb-0">120</h3>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row): ?>
                                <tr>
                                    <td>#<?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['cliente']); ?></td>
                                    <td>R$ <?php echo number_format($row['valor'], 2, ',', '.'); ?></td>
                                    <td><span class="stat-badge"><?php echo htmlspecialchars($row['status']); ?></span></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['data'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
