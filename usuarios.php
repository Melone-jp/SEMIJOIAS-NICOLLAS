<?php
require __DIR__ . '/config.php';
if (!isLoggedIn()) { header('Location: login.php'); exit; }

$rows = [
    ['nome' => 'Carlos Almeida', 'cargo' => 'Gerente', 'vendas' => 42, 'comissao' => 18450.00],
    ['nome' => 'Patricia Silva', 'cargo' => 'Vendedora', 'vendas' => 38, 'comissao' => 16200.00],
    ['nome' => 'Renato Costa', 'cargo' => 'Supervisor', 'vendas' => 27, 'comissao' => 13480.00],
    ['nome' => 'Beatriz Rocha', 'cargo' => 'Consultora', 'vendas' => 24, 'comissao' => 11930.00],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários | Semijoias MR</title>
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
                <a class="nav-link" href="caixa.php">Caixa</a>
                <a class="nav-link active" href="usuarios.php">Usuários</a>
            </nav>
        </aside>
        <main class="col-lg-10 main-content">
            <div class="page-header">
                <div>
                    <p class="eyebrow">Usuários</p>
                    <h1 class="page-title">Performance por colaborador</h1>
                    <div class="page-subtitle">Acompanhamento comercial e produtividade</div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="user-pill"><span class="user-avatar"><?php echo strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)); ?></span><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Administrador'); ?></div>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">Sair</a>
                </div>
            </div>

            <div class="panel">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Colaborador</th>
                            <th>Cargo</th>
                            <th>Vendas</th>
                            <th>Comissão</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nome']); ?></td>
                                <td><?php echo htmlspecialchars($row['cargo']); ?></td>
                                <td><?php echo $row['vendas']; ?></td>
                                <td>R$ <?php echo number_format($row['comissao'], 2, ',', '.'); ?></td>
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
