<?php
require __DIR__ . '/config.php';
if (!isLoggedIn()) { header('Location: login.php'); exit; }

$rows = [
    ['mes' => 'Janeiro', 'valor' => 18540.00, 'meta' => 18000],
    ['mes' => 'Fevereiro', 'valor' => 21030.00, 'meta' => 20000],
    ['mes' => 'Março', 'valor' => 19880.00, 'meta' => 21000],
    ['mes' => 'Abril', 'valor' => 22460.00, 'meta' => 22000],
    ['mes' => 'Maio', 'valor' => 24110.00, 'meta' => 23000],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comissões | Semijoias MR</title>
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
                <a class="nav-link active" href="comissoes.php">Comissões</a>
                <a class="nav-link" href="caixa.php">Caixa</a>
                <a class="nav-link" href="usuarios.php">Usuários</a>
            </nav>
        </aside>
        <main class="col-lg-10 main-content">
            <div class="page-header">
                <div>
                    <p class="eyebrow">Comissões</p>
                    <h1 class="page-title">Relatório de comissões</h1>
                    <div class="page-subtitle">Acompanhamento de remuneração por período</div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="user-pill"><span class="user-avatar"><?php echo strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)); ?></span><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Administrador'); ?></div>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">Sair</a>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="module-card">
                        <div class="text-muted small mb-2">Total no período</div>
                        <h3 class="mb-0">R$ 106.020,00</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="module-card">
                        <div class="text-muted small mb-2">Meta vencida</div>
                        <h3 class="mb-0">R$ 97.500,00</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="module-card">
                        <div class="text-muted small mb-2">Taxa de alcance</div>
                        <h3 class="mb-0">108,7%</h3>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Mês</th>
                                <th>Valor</th>
                                <th>Meta</th>
                                <th>Desempenho</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row): ?>
                                <?php $percent = ($row['valor'] / max($row['meta'], 1)) * 100; ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['mes']); ?></td>
                                    <td>R$ <?php echo number_format($row['valor'], 2, ',', '.'); ?></td>
                                    <td>R$ <?php echo number_format($row['meta'], 2, ',', '.'); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1">
                                                <div class="progress-bar bg-success" style="width: <?php echo min($percent, 100); ?>%"></div>
                                            </div>
                                            <small><?php echo number_format($percent, 1, ',', '.'); ?>%</small>
                                        </div>
                                    </td>
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
