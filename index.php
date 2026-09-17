<?php
require_once 'php/db.php';
$flash = $_SESSION['flash'] ?? null;
session_start();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$total = $pdo->query('SELECT COUNT(*) AS total FROM livres')->fetch()['total'];
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bibliothèque en Ligne</title>
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

    <div class="hero">
        <h2>Bienvenue dans votre Bibliothèque en Ligne</h2>
        <p>Recherchez parmi nos <strong><?= $total ?></strong> livres disponibles.</p>
        <p>Ajoutez vos livres préférés à votre liste de lecture et gérez facilement votre collection.</p>
        <form class="search-form" action="results.php" method="get">
            <input type="text" name="q" placeholder="Titre ou auteur...">
            <button class="btn" type="submit">Rechercher</button>
        </form>
    </div>

    <h2 style="margin-bottom:16px">Derniers livres ajoutés</h2>
    <?php
    $livres = $pdo->query('SELECT * FROM livres ORDER BY id DESC LIMIT 4')->fetchAll();
    foreach ($livres as $livre):
    ?>
    <div class="card">
        <div>
            <h3><?= htmlspecialchars($livre['titre']) ?></h3>
            <p><?= htmlspecialchars($livre['auteur']) ?> — <?= htmlspecialchars($livre['maison_edition'] ?? '') ?></p>
        </div>
        <div class="card-actions">
            <a class="btn secondary" href="details.php?id=<?= $livre['id'] ?>">Détails</a>
            <a class="btn" href="modifier.php?id=<?= $livre['id'] ?>">Modifier</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<footer>© 2026 — Bibliothèque en Ligne — AVOHOU Sergio</footer>
<script src="js/app.js"></script>
</body>
</html>