<?php
include 'db.php'; 

header('Content-Type: application/json'); 

$searchTerm = $_POST['term'] ?? ''; 

if (empty($searchTerm)) {
    echo json_encode([]); 
    exit();
}

try {
    
    $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE name LIKE ? AND price > 0 LIMIT 10");
    $stmt->execute(['%' . $searchTerm . '%']);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results); 
} catch (PDOException $e) {
    
    error_log("Database error in Search_games.php: " . $e->getMessage());
    echo json_encode(['error' => 'A apărut o eroare la căutare.']);
}
?>