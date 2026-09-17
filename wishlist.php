<?php
require_once 'php/db.php';
session_start();

$id_lecteur = 1; // Lecteur par défaut

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Retirer un livre de la liste
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['retirer'])) {
    $id_livre = (int)$_POST['id_livre'];
    $stmt = $pdo->prepare('DELETE FROM liste_lecture WHERE id_livre = :l AND id_lecteur = :u');
    $stmt->execute([':l' => $id_livre, ':u' => $id_lecteur]);
    $_SESSION['flash'] = ['type' => 'ok', 'message' => 'Livre retiré de votre liste de lecture.'];
    header('Location: wishlist.php');
    exit;
}

$stmt = $pdo->prepare('
    SELECT l.*, ll.date_emprunt, ll.date_retour
    FROM liste_lecture ll
    JOIN livres l ON l.id = ll.id_livre
    WHERE ll.id_lecteur = :u
    ORDER BY ll.date_emprunt DESC
');
$stmt->execute([':u' => $id_lecteur]);
$liste = $stmt->fetchAll();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste de lecture — Bibliothèque</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <h1>📚 Bibliothèque en Ligne</h1>
    <nav>
        <a href="index.php">Accueil</a>
        <a href="wishlist.php">Liste de lecture</a>
        <a href="ajouter.php">Ajouter un livre</a>
    </nav>
</header>

<div class="container">
    <h2 style="margin-bottom:16px">Ma liste de lecture</h2>

    <?php if ($flash): ?>
        <div class="alert <?= htmlspecialchars($flash['type']) ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($liste)): ?>
        <div class="alert error">Votre liste de lecture est vide.</div>
    <?php else: ?>
        <table>
            <tr>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Date emprunt</th>
                <th>Date retour</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($liste as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['titre']) ?></td>
                <td><?= htmlspecialchars($item['auteur']) ?></td>
                <td><?= htmlspecialchars($item['date_emprunt']) ?></td>
                <td><?= htmlspecialchars($item['date_retour'] ?? 'En cours') ?></td>
                <td>
                    <a class="btn secondary" href="details.php?id=<?= $item['id'] ?>">Détails</a>
                    <form method="post" style="display:inline" class="delete-form">
                        <input type="hidden" name="id_livre" value="<?= $item['id'] ?>">
                        <button class="btn danger" name="retirer" type="submit">Retirer</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <br>
    <a class="btn secondary" href="index.php">Retour à l'accueil</a>
</div>

<footer>© 2026 — Bibliothèque en Ligne — AVOHOU Sergio</footer>
<script src="js/app.js"></script>
</body>
</html>