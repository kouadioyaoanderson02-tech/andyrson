<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../connexion.php"); exit;
}
require '../includes/connexion_db.php';

$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO locataires (nom, prenom, email, telephone, bien_id, date_debut, date_fin) VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([$_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['telephone'],
                    $_POST['bien_id'] ?: null, $_POST['date_debut'] ?: null, $_POST['date_fin'] ?: null]);
    // Mettre à jour le statut du bien
    if ($_POST['bien_id']) {
        $pdo->prepare("UPDATE biens SET statut='loue' WHERE id=?")->execute([$_POST['bien_id']]);
    }
    $succes = "Locataire ajouté !";
}

$locataires = $pdo->query("SELECT l.*, b.titre as bien_titre FROM locataires l LEFT JOIN biens b ON l.bien_id = b.id ORDER BY l.id DESC")->fetchAll();
$biens_dispo = $pdo->query("SELECT id, titre FROM biens WHERE statut IN ('disponible','loue')")->fetchAll();
?>
<?php include '../includes/header.php'; ?>

<div class="admin-layout">
    <aside class="sidebar">
        <div class="sidebar-title">⚙️ Administration</div>
        <nav class="sidebar-nav">
            <a href="dashboard.php">📊 Dashboard</a>
            <a href="biens.php">🏠 Biens</a>
            <a href="ajouter_bien.php">➕ Ajouter un bien</a>
            <a href="locataires.php" class="active">👥 Locataires</a>
            <a href="paiements.php">💳 Paiements</a>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="admin-header"><h1>Locataires</h1></div>
        <?php if ($succes): ?><div class="alert alert-success"><?= $succes ?></div><?php endif; ?>

        <!-- Formulaire ajout -->
        <div class="admin-card">
            <h3>Ajouter un locataire</h3>
            <form method="POST" class="admin-form">
                <div class="form-row">
                    <div class="form-group"><label>Nom</label><input type="text" name="nom" required></div>
                    <div class="form-group"><label>Prénom</label><input type="text" name="prenom" required></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Email</label><input type="email" name="email"></div>
                    <div class="form-group"><label>Téléphone</label><input type="tel" name="telephone"></div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Bien loué</label>
                        <select name="bien_id">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($biens_dispo as $b): ?>
                                <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['titre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group"><label>Date début</label><input type="date" name="date_debut"></div>
                    <div class="form-group"><label>Date fin</label><input type="date" name="date_fin"></div>
                </div>
                <button type="submit" class="btn-primary">Ajouter</button>
            </form>
        </div>

        <!-- Liste -->
        <div class="admin-card">
            <h3>Liste des locataires</h3>
            <table class="admin-table">
                <thead>
                    <tr><th>Nom</th><th>Prénom</th><th>Email</th><th>Téléphone</th><th>Bien</th><th>Début</th><th>Fin</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($locataires as $l): ?>
                    <tr>
                        <td><?= htmlspecialchars($l['nom']) ?></td>
                        <td><?= htmlspecialchars($l['prenom']) ?></td>
                        <td><?= htmlspecialchars($l['email']) ?></td>
                        <td><?= htmlspecialchars($l['telephone']) ?></td>
                        <td><?= htmlspecialchars($l['bien_titre'] ?? '-') ?></td>
                        <td><?= $l['date_debut'] ?? '-' ?></td>
                        <td><?= $l['date_fin'] ?? '-' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
