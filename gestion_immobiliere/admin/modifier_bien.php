<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../connexion.php"); exit;
}
require '../includes/connexion_db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM biens WHERE id = ?");
$stmt->execute([$id]);
$bien = $stmt->fetch();
if (!$bien) { header("Location: biens.php"); exit; }

$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $photo = $bien['photo'];
    if (!empty($_FILES['photo']['name'])) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/photos_biens/$photo");
    }
    $stmt = $pdo->prepare("UPDATE biens SET titre=?, type=?, statut=?, prix=?, surface=?, nb_pieces=?, adresse=?, ville=?, description=?, photo=? WHERE id=?");
    $stmt->execute([$_POST['titre'], $_POST['type'], $_POST['statut'], $_POST['prix'],
                    $_POST['surface'] ?: null, $_POST['nb_pieces'] ?: null,
                    $_POST['adresse'], $_POST['ville'], $_POST['description'], $photo, $id]);
    $succes = "Bien modifié avec succès !";
    $stmt = $pdo->prepare("SELECT * FROM biens WHERE id = ?");
    $stmt->execute([$id]);
    $bien = $stmt->fetch();
}
?>
<?php include '../includes/header.php'; ?>

<div class="admin-layout">
    <aside class="sidebar">
        <div class="sidebar-title">⚙️ Administration</div>
        <nav class="sidebar-nav">
            <a href="dashboard.php">📊 Dashboard</a>
            <a href="biens.php" class="active">🏠 Biens</a>
            <a href="ajouter_bien.php">➕ Ajouter un bien</a>
            <a href="locataires.php">👥 Locataires</a>
            <a href="paiements.php">💳 Paiements</a>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="admin-header"><h1>Modifier le bien</h1></div>
        <?php if ($succes): ?><div class="alert alert-success"><?= $succes ?></div><?php endif; ?>

        <div class="admin-card">
            <form method="POST" enctype="multipart/form-data" class="admin-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Titre *</label>
                        <input type="text" name="titre" value="<?= htmlspecialchars($bien['titre']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Type *</label>
                        <select name="type">
                            <?php foreach (['appartement','maison','villa','bureau','terrain'] as $t): ?>
                                <option value="<?= $t ?>" <?= $bien['type']===$t?'selected':'' ?>><?= ucfirst($t) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Prix (€) *</label>
                        <input type="number" name="prix" value="<?= $bien['prix'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="statut">
                            <?php foreach (['disponible','loue','vendu'] as $s): ?>
                                <option value="<?= $s ?>" <?= $bien['statut']===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Surface (m²)</label>
                        <input type="number" name="surface" value="<?= $bien['surface'] ?>">
                    </div>
                    <div class="form-group">
                        <label>Pièces</label>
                        <input type="number" name="nb_pieces" value="<?= $bien['nb_pieces'] ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Adresse</label>
                        <input type="text" name="adresse" value="<?= htmlspecialchars($bien['adresse']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Ville</label>
                        <input type="text" name="ville" value="<?= htmlspecialchars($bien['ville']) ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4"><?= htmlspecialchars($bien['description']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Photo (laisser vide pour garder l'actuelle)</label>
                    <input type="file" name="photo" accept="image/*">
                </div>
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="biens.php" class="btn-outline-sm">Annuler</a>
            </form>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
