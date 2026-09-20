<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'semijoiasmr';

mysqli_report(MYSQLI_REPORT_OFF);

try {
    $conn = new mysqli($host, $user, $password, $database);
    $conn->set_charset('utf8mb4');
} catch (Exception $e) {
    $dbError = 'Não foi possível conectar ao banco de dados. Verifique o MySQL do phpMyAdmin e as credenciais.';
    $conn = null;
}

function isLoggedIn() {
    return !empty($_SESSION['user_logged_in']);
}

function loginUser($username, $password) {
    $username = trim((string) $username);
    $password = (string) $password;

    $users = [
        'admin' => [
            'password' => 'admin123',
            'name' => 'Administrador',
            'role' => 'admin',
        ],
        'usuario' => [
            'password' => 'usuario123',
            'name' => 'Usuário',
            'role' => 'usuario',
        ],
    ];

    $normalizedUsername = strtolower($username);
    if (!isset($users[$normalizedUsername])) {
        return false;
    }

    $account = $users[$normalizedUsername];
    if ($password !== $account['password']) {
        return false;
    }

    $_SESSION['user_logged_in'] = true;
    $_SESSION['user_name'] = $account['name'];
    $_SESSION['user_role'] = $account['role'];
    return true;
}

function logoutUser() {
    unset($_SESSION['user_logged_in'], $_SESSION['user_name']);
    session_destroy();
}

function fetchScalar($conn, $query, $default = 0) {
    if (!$conn) {
        return $default;
    }

    $result = $conn->query($query);
    if (!$result) {
        return $default;
    }

    $row = $result->fetch_row();
    if (!$row || !isset($row[0])) {
        return $default;
    }

    $value = $row[0];
    return $value === null ? $default : $value;
}

function fetchScalarPrepared($conn, $query, $params = [], $default = 0) {
    if (!$conn) {
        return $default;
    }

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return $default;
    }

    if (!empty($params)) {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param) || is_double($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }

        $stmt->bind_param($types, ...$params);
    }

    $executed = $stmt->execute();
    if (!$executed) {
        $stmt->close();
        return $default;
    }

    $result = $stmt->get_result();
    $stmt->close();

    if (!$result) {
        return $default;
    }

    $row = $result->fetch_row();
    if (!$row || !isset($row[0])) {
        return $default;
    }

    $value = $row[0];
    return $value === null ? $default : $value;
}

function fetchAll($conn, $query) {
    if (!$conn) {
        return [];
    }

    $result = $conn->query($query);
    if (!$result) {
        return [];
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

function fetchAllPrepared($conn, $query, $params = []) {
    if (!$conn) {
        return [];
    }

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return [];
    }

    if (!empty($params)) {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param) || is_double($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }

        $stmt->bind_param($types, ...$params);
    }

    $executed = $stmt->execute();
    if (!$executed) {
        $stmt->close();
        return [];
    }

    $result = $stmt->get_result();
    $stmt->close();

    if (!$result) {
        return [];
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}
