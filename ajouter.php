<?php
require_once 'php/db.php';
session_start();

$errors = [];
$values = ['titre'=>'', 'auteur'=>'', 'description'=>'', 'maison_edition'=>'', 'nombre_exemplaire'=>1];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['titre']             = trim($_POST['titre'] ?? '');
    $values['auteur']            = trim($_POST['auteur'] ?? '');
    $values['description']       = trim($_POST['description'] ?? '');
    $values['maison_edition']    = trim($_POST['maison_edition'] ?? '');
    $values['nombre_exemplaire'] = (int)($_POST['nombre_exemplaire'] ?? 1);

    if (mb_strlen($values['titre']) < 2)  $errors['titre']  = 'Titre invalide (min 2 caractères).';
    if (mb_strlen($values['auteur']) < 2) $errors['auteur'] = 'Auteur invalide (min 2 caractères).';
    if ($values['nombre_exemplaire'] < 1) $errors['nombre_exemplaire'] = 'Nombre invalide (min 1).';

    if (empty($errors)) {
        $stmt = $pdo->prepare('INSERT INTO livres (titre, auteur, description, maison_edition, nombre_exemplaire) VALUES (:t, :a, :d, :m, :n)');
        $stmt->execute([
            ':t' => $values['titre'],
            ':a' => $values['auteur'],
            ':d' => $values['description'],
            ':m' => $values['maison_edition'],
            ':n' => $values['nombre_exemplaire']
        ]);
        $_SESSION['flash'] = ['type' => 'ok', 'message' => 'Livre ajouté avec succès.'];
        header('Location: index.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter un livre — Bibliothèque</title>
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
    <h2 style="margin-bottom:16px">Ajouter un livre</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert error">Veuillez corriger les erreurs ci-dessous.</div>
    <?php endif; ?>

    <div class="form-card">
        <form id="livre-form" method="post">
            <label>Titre *</label>
            <input type="text" name="titre" value="<?= htmlspecialchars($values['titre']) ?>">
            <?php if (isset($errors['titre'])): ?>
                <small style="color:var(--err)"><?= htmlspecialchars($errors['titre']) ?></small>
            <?php endif; ?>

            <label>Auteur *</label>
            <input type="text" name="auteur" value="<?= htmlspecialchars($values['auteur']) ?>">
            <?php if (isset($errors['auteur'])): ?>
                <small style="color:var(--err)"><?= htmlspecialchars($errors['auteur']) ?></small>
            <?php endif; ?>

            <label>Description</label>
            <textarea name="description"><?= htmlspecialchars($values['description']) ?></textarea>

            <label>Maison d'édition</label>
            <input type="text" name="maison_edition" value="<?= htmlspecialchars($values['maison_edition']) ?>">

            <label>Nombre d'exemplaires *</label>
            <input type="number" name="nombre_exemplaire" min="1" value="<?= htmlspecialchars($values['nombre_exemplaire']) ?>">
            <?php if (isset($errors['nombre_exemplaire'])): ?>
                <small style="color:var(--err)"><?= htmlspecialchars($errors['nombre_exemplaire']) ?></small>
            <?php endif; ?>

            <br><br>
            <button class="btn" type="submit">Ajouter le livre</button>
            <a class="btn secondary" href="index.php">Annuler</a>
        </form>
    </div>
</div>

<footer>© 2026 — Bibliothèque en Ligne — AVOHOU Sergio</footer>
<script src="js/app.js"></script>
</body>
</html>