<?php
require_once 'php/db.php';
session_start();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM livres WHERE id = :id');
$stmt->execute([':id' => $id]);
$livre = $stmt->fetch();

if (!$livre) {
    header('Location: index.php');
    exit;
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Ajouter à la liste de lecture
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_liste'])) {
    $id_lecteur = 1; // Lecteur par défaut
    $date_emprunt = date('Y-m-d');
    try {
        $stmt = $pdo->prepare('INSERT INTO liste_lecture (id_livre, id_lecteur, date_emprunt) VALUES (:l, :u, :d)');
        $stmt->execute([':l' => $id, ':u' => $id_lecteur, ':d' => $date_emprunt]);
        $_SESSION['flash'] = ['type' => 'ok', 'message' => 'Livre ajouté à votre liste de lecture.'];
    } catch (PDOException $e) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Ce livre est déjà dans votre liste de lecture.'];
    }
    header('Location: details.php?id=' . $id);
    exit;
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($livre['titre']) ?> — Bibliothèque</title>
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
    <?php if ($flash): ?>
        <div class="alert <?= htmlspecialchars($flash['type']) ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>

    <div class="detail-card">
        <h2><?= htmlspecialchars($livre['titre']) ?></h2>
        <p><strong>Auteur :</strong> <?= htmlspecialchars($livre['auteur']) ?></p>
        <p><strong>Maison d'édition :</strong> <?= htmlspecialchars($livre['maison_edition'] ?? 'Non renseigné') ?></p>
        <p><strong>Exemplaires disponibles :</strong> <?= htmlspecialchars($livre['nombre_exemplaire']) ?></p>
        <p><strong>Description :</strong> <?= htmlspecialchars($livre['description'] ?? 'Aucune description.') ?></p>

        <br>
        <form method="post" style="display:inline">
            <button class="btn" name="ajouter_liste" type="submit">+ Ajouter à ma liste de lecture</button>
        </form>
        <a class="btn secondary" href="modifier.php?id=<?= $livre['id'] ?>">Modifier</a>
        <form class="delete-form" method="post" action="supprimer.php" style="display:inline">
            <input type="hidden" name="id" value="<?= $livre['id'] ?>">
            <button class="btn danger" type="submit">Supprimer</button>
        </form>
    </div>

    <br>
    <a class="btn secondary" href="index.php">Retour à l'accueil</a>
</div>

<footer>© 2026 — Bibliothèque en Ligne — AVOHOU Sergio</footer>
<script src="js/app.js"></script>
</body>
</html>