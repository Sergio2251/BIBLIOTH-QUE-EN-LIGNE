<?php
require_once 'php/db.php';
session_start();

$q = trim($_GET['q'] ?? '');
$livres = [];

if ($q !== '') {
$stmt = $pdo->prepare('SELECT * FROM livres WHERE titre LIKE :q1 OR auteur LIKE :q2 ORDER BY titre ASC');
$stmt->execute([':q1' => '%' . $q . '%', ':q2' => '%' . $q . '%']);
    $livres = $stmt->fetchAll();
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Résultats — Bibliothèque</title>
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
    <h2 style="margin-bottom:16px">
        Résultats pour : <em style="color:var(--accent)"><?= htmlspecialchars($q) ?></em>
    </h2>

    <form class="search-form" action="results.php" method="get" style="justify-content:flex-start;margin-bottom:24px">
        <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Titre ou auteur...">
        <button class="btn" type="submit">Rechercher</button>
    </form>

    <?php if ($q === ''): ?>
        <div class="alert error">Veuillez saisir un terme de recherche.</div>
    <?php elseif (empty($livres)): ?>
        <div class="alert error">Aucun livre trouvé pour "<?= htmlspecialchars($q) ?>".</div>
    <?php else: ?>
        <p style="color:var(--muted);margin-bottom:16px"><?= count($livres) ?> livre(s) trouvé(s)</p>
        <?php foreach ($livres as $livre): ?>
        <div class="card">
            <div>
                <h3><?= htmlspecialchars($livre['titre']) ?></h3>
                <p><?= htmlspecialchars($livre['auteur']) ?> — <?= htmlspecialchars($livre['maison_edition'] ?? '') ?></p>
                <p>Exemplaires : <?= htmlspecialchars($livre['nombre_exemplaire']) ?></p>
            </div>
            <div class="card-actions">
                <a class="btn secondary" href="details.php?id=<?= $livre['id'] ?>">Voir détails</a>
                <a class="btn" href="modifier.php?id=<?= $livre['id'] ?>">Modifier</a>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a class="btn secondary" href="index.php">Retour à l'accueil</a>
</div>

<footer>© 2026 — Bibliothèque en Ligne — AVOHOU Sergio</footer>
<script src="js/app.js"></script>
</body>
</html>