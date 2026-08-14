<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../connexion.php"); exit;
}
require '../includes/connexion_db.php';
$biens = $pdo->query("SELECT * FROM biens ORDER BY created_at DESC")->fetchAll();
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
        <div class="admin-header">
            <h1>Gestion des biens</h1>
            <a href="ajouter_bien.php" class="btn-primary">+ Ajouter un bien</a>
        </div>

        <div class="admin-card">
            <table class="admin-table">
                <thead>
                    <tr><th>#</th><th>Titre</th><th>Type</th><th>Ville</th><th>Surface</th><th>Prix</th><th>Statut</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($biens as $b): ?>
                    <tr>
                        <td><?= $b['id'] ?></td>
                        <td><?= htmlspecialchars($b['titre']) ?></td>
                        <td><?= ucfirst($b['type']) ?></td>
                        <td><?= htmlspecialchars($b['ville']) ?></td>
                        <td><?= $b['surface'] ? $b['surface'].' m²' : '-' ?></td>
                        <td><?= number_format($b['prix'], 0, ',', ' ') ?> €</td>
                        <td><span class="badge-statut statut-<?= $b['statut'] ?>"><?= ucfirst($b['statut']) ?></span></td>
                        <td>
                            <a href="modifier_bien.php?id=<?= $b['id'] ?>" class="btn-edit">✏️ Modifier</a>
                            <a href="supprimer_bien.php?id=<?= $b['id'] ?>" class="btn-delete" onclick="return confirm('Supprimer ?')">🗑️ Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
