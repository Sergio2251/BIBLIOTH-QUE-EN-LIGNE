<?php
require_once 'php/db.php';
session_start();

$id = (int)($_POST['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare('DELETE FROM livres WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $_SESSION['flash'] = ['type' => 'ok', 'message' => 'Livre supprimé avec succès.'];
} else {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Livre introuvable.'];
}

header('Location: index.php');
exit;