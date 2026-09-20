<?php
require __DIR__ . '/config.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

function formatMoney($value) {
    return 'R$ ' . number_format((float) $value, 2, ',', '.');
}

$dateStart = isset($_GET['date_start']) && $_GET['date_start'] !== '' ? $_GET['date_start'] : date('Y-m-d', strtotime('-30 days'));
$dateEnd = isset($_GET['date_end']) && $_GET['date_end'] !== '' ? $_GET['date_end'] : date('Y-m-d');

$metrics = [
    'clientes' => 128,
    'pedidos' => 1540,
    'comissoes' => 45680.25,
    'caixa' => 98240.75,
];

$monthlyData = [1200, 1550, 1380, 1680, 2100, 1980, 2250, 2470, 2320, 2710, 2650, 2990];
$cashBreakdown = ['entradas' => 72, 'saidas' => 28];

if ($conn) {
    $metrics['clientes'] = (int) fetchScalar($conn, 'SELECT COUNT(*) FROM tb_usuario');
    $metrics['pedidos'] = (int) fetchScalarPrepared($conn, 'SELECT COUNT(*) FROM tb_pedido WHERE dt_pedido BETWEEN ? AND ?', [$dateStart, $dateEnd], 0);
    $metrics['comissoes'] = (float) fetchScalarPrepared($conn, 'SELECT COALESCE(SUM(valor_comissao), 0) FROM tb_comissao WHERE dt_registro BETWEEN ? AND ?', [$dateStart, $dateEnd], 0);
    $metrics['caixa'] = (float) fetchScalarPrepared($conn, 'SELECT COALESCE(SUM(valor_entrada - valor_saida), 0) FROM tb_caixa WHERE dt_caixa BETWEEN ? AND ?', [$dateStart, $dateEnd], 0);
}

$topUsers = [
    ['nome' => 'Marina', 'pedidos' => 89],
    ['nome' => 'Pedro', 'pedidos' => 74],
    ['nome' => 'Ana', 'pedidos' => 63],
    ['nome' => 'Lucas', 'pedidos' => 58],
    ['nome' => 'Beatriz', 'pedidos' => 46],
];

if ($conn) {
    $userCols = [];
    $userColumns = $conn->query('SHOW COLUMNS FROM tb_usuario');
    if ($userColumns) {
        while ($column = $userColumns->fetch_assoc()) {
            $userCols[] = $column['Field'];
        }
    }

    if (in_array('cd_usuario', $userCols) && in_array('nm_usuario', $userCols)) {
        $topUsers = fetchAll($conn, "SELECT u.nm_usuario AS nome, COUNT(p.cd_pedido) AS pedidos FROM tb_usuario u LEFT JOIN tb_pedido p ON p.id_usuario = u.cd_usuario AND p.dt_pedido BETWEEN '$dateStart' AND '$dateEnd' GROUP BY u.cd_usuario, u.nm_usuario ORDER BY pedidos DESC LIMIT 5");
    } elseif (in_array('cd_usuario', $userCols)) {
        $topUsers = fetchAll($conn, "SELECT cd_usuario AS nome, 0 AS pedidos FROM tb_usuario ORDER BY cd_usuario ASC LIMIT 5");
    }
}

$recentOrders = [
    ['cd_pedido' => 1584, 'valor_total' => 1350.00, 'dt_pedido' => date('Y-m-d')],
    ['cd_pedido' => 1583, 'valor_total' => 980.50, 'dt_pedido' => date('Y-m-d', strtotime('-1 day'))],
    ['cd_pedido' => 1582, 'valor_total' => 2100.00, 'dt_pedido' => date('Y-m-d', strtotime('-2 day'))],
    ['cd_pedido' => 1581, 'valor_total' => 760.25, 'dt_pedido' => date('Y-m-d', strtotime('-3 day'))],
    ['cd_pedido' => 1580, 'valor_total' => 3300.75, 'dt_pedido' => date('Y-m-d', strtotime('-4 day'))],
];

if ($conn) {
    $recentOrders = fetchAll($conn, "SELECT cd_pedido, valor AS valor_total, dt_pedido FROM tb_pedido WHERE dt_pedido BETWEEN '$dateStart' AND '$dateEnd' ORDER BY cd_pedido DESC LIMIT 5");
    if (empty($recentOrders)) {
        $recentOrders = fetchAll($conn, 'SELECT cd_pedido, valor AS valor_total, dt_pedido FROM tb_pedido ORDER BY cd_pedido DESC LIMIT 5');
    }
}

if ($conn) {
    $monthlyQuery = $conn->query("SELECT MONTH(dt_registro) AS mes, SUM(valor_comissao) AS total FROM tb_comissao WHERE dt_registro BETWEEN '$dateStart' AND '$dateEnd' GROUP BY MONTH(dt_registro) ORDER BY mes");
    if ($monthlyQuery && $monthlyQuery->num_rows > 0) {
        $monthlyData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        while ($row = $monthlyQuery->fetch_assoc()) {
            $monthIndex = (int) $row['mes'] - 1;
            $monthlyData[$monthIndex] = (float) $row['total'];
        }
    }

    $cashQuery = $conn->query("SELECT COALESCE(SUM(valor_entrada), 0) AS entradas, COALESCE(SUM(valor_saida), 0) AS saidas FROM tb_caixa WHERE dt_caixa BETWEEN '$dateStart' AND '$dateEnd'");
    if ($cashQuery && $cashQuery->num_rows > 0) {
        $cashRow = $cashQuery->fetch_assoc();
        $totalEntradas = (float) ($cashRow['entradas'] ?? 0);
        $totalSaidas = (float) ($cashRow['saidas'] ?? 0);
        $cashBreakdown = [
            'entradas' => $totalEntradas > 0 ? round(($totalEntradas / max($totalEntradas + $totalSaidas, 1)) * 100, 0) : 0,
            'saidas' => $totalSaidas > 0 ? round(($totalSaidas / max($totalEntradas + $totalSaidas, 1)) * 100, 0) : 0,
        ];
    }
}

$summaryCards = [
    ['title' => 'Usuários', 'value' => $metrics['clientes'] ?? 0, 'icon' => 'US', 'prefix' => '', 'suffix' => '', 'decimals' => 0, 'bg' => 'primary'],
    ['title' => 'Pedidos', 'value' => $metrics['pedidos'] ?? 0, 'icon' => 'PD', 'prefix' => '', 'suffix' => '', 'decimals' => 0, 'bg' => 'success'],
    ['title' => 'Comissões', 'value' => $metrics['comissoes'] ?? 0, 'icon' => 'CM', 'prefix' => 'R$ ', 'suffix' => '', 'decimals' => 2, 'bg' => 'warning'],
    ['title' => 'Fluxo caixa', 'value' => $metrics['caixa'] ?? 0, 'icon' => 'CX', 'prefix' => 'R$ ', 'suffix' => '', 'decimals' => 2, 'bg' => 'danger'],
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Semijoias MR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <a class="nav-link active" href="index.php">Dashboard</a>
                    <a class="nav-link" href="pedidos.php">Pedidos</a>
                    <a class="nav-link" href="comissoes.php">Comissões</a>
                    <a class="nav-link" href="caixa.php">Caixa</a>
                    <a class="nav-link" href="usuarios.php">Usuários</a>
                </nav>
            </aside>

            <main class="col-lg-10 main-content">
                <div class="topbar">
                    <div>
                        <p class="eyebrow">Operação</p>
                        <h1 class="h3 mb-0 fw-bold">Dashboard Geral</h1>
                        <small class="text-muted">Visão executiva do banco de dados</small>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="user-pill">
                            <span class="user-avatar"><?php echo strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)); ?></span>
                            <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Administrador'); ?>
                        </div>
                        <a href="logout.php" class="btn btn-outline-danger btn-sm">Sair</a>
                    </div>
                </div>

                <?php if (isset($dbError)): ?>
                    <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($dbError); ?>
                        <div class="mt-2">Ajuste o usuário/senha do MySQL em <strong>config.php</strong> para conectar ao banco.</div>
                    </div>
                <?php endif; ?>

                <div class="panel mb-4">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Data inicial</label>
                            <input type="date" class="form-control" name="date_start" value="<?php echo htmlspecialchars($dateStart); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Data final</label>
                            <input type="date" class="form-control" name="date_end" value="<?php echo htmlspecialchars($dateEnd); ?>">
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">Aplicar filtro</button>
                            <a href="index.php" class="btn btn-outline-secondary">Limpar</a>
                        </div>
                    </form>
                </div>

                <div class="row g-4 mb-4">
                    <?php foreach ($summaryCards as $card): ?>
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="stat-icon bg-<?php echo $card['bg']; ?>"><?php echo $card['icon']; ?></div>
                                    <span class="badge-soft bg-light text-dark"><?php echo date('d/m', strtotime($dateStart)); ?> - <?php echo date('d/m', strtotime($dateEnd)); ?></span>
                                </div>
                                <small><?php echo $card['title']; ?></small>
                                <h3 class="mt-2" data-counter="<?php echo $card['value']; ?>" data-prefix="<?php echo $card['prefix']; ?>" data-suffix="<?php echo $card['suffix']; ?>" data-decimals="<?php echo $card['decimals']; ?>"><?php echo $card['prefix'] . number_format($card['value'], $card['decimals'], ',', '.') . $card['suffix']; ?></h3>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-xl-8">
                        <div class="panel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h2 class="h5 mb-0 fw-bold">Comissões por mês</h2>
                                <span class="badge-soft bg-primary-subtle text-primary"><?php echo date('Y', strtotime($dateEnd)); ?></span>
                            </div>
                            <div class="chart-wrap">
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="panel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h2 class="h5 mb-0 fw-bold">Fluxo de caixa</h2>
                                <span class="text-success value-up"><?php echo $cashBreakdown['entradas']; ?>%</span>
                            </div>
                            <div class="chart-wrap">
                                <canvas id="cashChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-xl-7">
                        <div class="panel">
                            <h2 class="h5 mb-3 fw-bold">Top usuários</h2>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Usuário</th>
                                            <th>Pedidos</th>
                                            <th>Participação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($topUsers)): ?>
                                            <?php foreach ($topUsers as $index => $user): ?>
                                                <?php $share = !empty($metrics['pedidos']) ? ($user['pedidos'] / max($metrics['pedidos'], 1)) * 100 : 0; ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($user['nome'] ?? 'Sem nome'); ?></td>
                                                    <td><?php echo (int) $user['pedidos']; ?></td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="progress flex-grow-1">
                                                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo min($share, 100); ?>%"></div>
                                                            </div>
                                                            <small><?php echo number_format($share, 1, ',', '.'); ?>%</small>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-muted text-center">Nenhum dado disponível.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-5">
                        <div class="panel">
                            <h2 class="h5 mb-3 fw-bold">Últimos pedidos</h2>
                            <div class="list-group list-group-flush">
                                <?php if (!empty($recentOrders)): ?>
                                    <?php foreach ($recentOrders as $order): ?>
                                        <?php $orderValue = isset($order['valor_total']) ? $order['valor_total'] : ($order['valor'] ?? 0); ?>
                                        <div class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="fw-bold">Pedido #<?php echo (int) ($order['cd_pedido'] ?? 0); ?></div>
                                                    <small class="text-muted"><?php echo !empty($order['dt_pedido']) ? date('d/m/Y', strtotime($order['dt_pedido'])) : 'Sem data'; ?></small>
                                                </div>
                                                <span class="fw-semibold text-success">R$ <?php echo number_format((float) $orderValue, 2, ',', '.'); ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-muted text-center py-3">Sem registros recentes.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>
