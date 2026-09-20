<?php
require __DIR__ . '/config.php';
logoutUser();
header('Location: login.php');
exit;
