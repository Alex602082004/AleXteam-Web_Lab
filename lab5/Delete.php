<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: Home.php");
    exit();
}

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT image_path FROM products WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch();

    if ($product) {
        if ($product['image_path'] !== 'pngs/default.png' && file_exists($product['image_path'])) {
            unlink($product['image_path']);
        }
        $delete = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $delete->execute([$_GET['id']]);
    }
}
header("Location: Store.php");
exit();
?>