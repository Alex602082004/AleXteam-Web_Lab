<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$gameId = $_POST['id'] ?? null;
$gameName = $_POST['name'] ?? null;
$gamePrice = $_POST['price'] ?? null;
$action = $_POST['action'] ?? null; 

if ($action === 'get_cart') {
    echo json_encode(['status' => 'success', 'cart' => $_SESSION['cart']]);
    exit();
}

if ($gameId && $gameName && $gamePrice && $action) {
    if ($action === 'add') {
        $_SESSION['cart'][$gameId] = ['name' => $gameName, 'price' => (float)$gamePrice];
        echo json_encode(['status' => 'success', 'action' => 'added', 'cart' => $_SESSION['cart']]);
    } elseif ($action === 'remove') {
        if (isset($_SESSION['cart'][$gameId])) {
            unset($_SESSION['cart'][$gameId]);
            echo json_encode(['status' => 'success', 'action' => 'removed', 'cart' => $_SESSION['cart']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Jocul nu este în coș.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Acțiune invalidă.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Date incomplete.']);
}
?>