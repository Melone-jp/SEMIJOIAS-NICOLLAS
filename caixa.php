<?php
require __DIR__ . '/config.php';
if (!isLoggedIn()) { header('Location: login.php'); exit; }

$rows = [
    ['data' => '2026-09-20', 'entrada' => 18200.00, 'saida' => 9300.00, 'saldo' => 8900.00],
    ['data' => '2026-09-19', 'entrada' => 17150.00, 'saida' => 7400.00, 'saldo' => 9750.00],
    ['data' => '2026-09-18', 'entrada' => 15120.00, 'saida' => 8900.00, 'saldo' => 6220.00],
    ['data' => '2026-09-17', 'entrada' => 16380.00, 'saida' => 9700.00, 'saldo' => 6680.00],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caixa | Semijoias MR</title>
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
                <a class="nav-link" href="pedidos.php">Pedidos</a>
                <a class="nav-link" href="comissoes.php">Comissões</a>
                <a class="nav-link active" href="caixa.php">Caixa</a>
                <a class="nav-link" href="usuarios.php">Usuários</a>
            </nav>
        </aside>
        <main class="col-lg-10 main-content">
            <div class="page-header">
                <div>
                    <p class="eyebrow">Caixa</p>
                    <h1 class="page-title">Fluxo financeiro</h1>
                    <div class="page-subtitle">Entradas, saídas e saldo diário</div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="user-pill"><span class="user-avatar"><?php echo strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)); ?></span><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Administrador'); ?></div>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">Sair</a>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="module-card">
                        <div class="text-muted small mb-2">Entradas</div>
                        <h3 class="mb-0">R$ 66.850,00</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="module-card">
                        <div class="text-muted small mb-2">Saídas</div>
                        <h3 class="mb-0">R$ 35.300,00</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="module-card">
                        <div class="text-muted small mb-2">Saldo</div>
                        <h3 class="mb-0">R$ 31.550,00</h3>
                    </div>
                </div>
            </div>

            <div class="panel">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Entrada</th>
                            <th>Saída</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($row['data'])); ?></td>
                                <td class="text-success">R$ <?php echo number_format($row['entrada'], 2, ',', '.'); ?></td>
                                <td class="text-danger">R$ <?php echo number_format($row['saida'], 2, ',', '.'); ?></td>
                                <td>R$ <?php echo number_format($row['saldo'], 2, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>
</body>
</html>
