<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../connexion.php"); exit;
}
require '../includes/connexion_db.php';

$total_biens      = $pdo->query("SELECT COUNT(*) FROM biens")->fetchColumn();
$total_disponibles = $pdo->query("SELECT COUNT(*) FROM biens WHERE statut='disponible'")->fetchColumn();
$total_loues      = $pdo->query("SELECT COUNT(*) FROM biens WHERE statut='loue'")->fetchColumn();
$total_locataires = $pdo->query("SELECT COUNT(*) FROM locataires")->fetchColumn();
$total_paiements  = $pdo->query("SELECT COUNT(*) FROM paiements")->fetchColumn();
$paiements_retard = $pdo->query("SELECT COUNT(*) FROM paiements WHERE statut='retard'")->fetchColumn();
$derniers_biens   = $pdo->query("SELECT * FROM biens ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>
<?php include '../includes/header.php'; ?>

<div class="admin-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-title">⚙️ Administration</div>
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="active">📊 Dashboard</a>
            <a href="biens.php">🏠 Biens</a>
            <a href="ajouter_bien.php">➕ Ajouter un bien</a>
            <a href="locataires.php">👥 Locataires</a>
            <a href="paiements.php">💳 Paiements</a>
        </nav>
    </aside>

    <!-- Contenu -->
    <main class="admin-main">
        <div class="admin-header">
            <h1>Dashboard</h1>
            <span>Bienvenue, <?= htmlspecialchars($_SESSION['user']['prenom']) ?> 👋</span>
        </div>

        <!-- Cartes stats -->
        <div class="stats-grid">
            <div class="stat-card stat-blue">
                <div class="stat-icon">🏠</div>
                <div><strong><?= $total_biens ?></strong><span>Total biens</span></div>
            </div>
            <div class="stat-card stat-green">
                <div class="stat-icon">✅</div>
                <div><strong><?= $total_disponibles ?></strong><span>Disponibles</span></div>
            </div>
            <div class="stat-card stat-orange">
                <div class="stat-icon">🔑</div>
                <div><strong><?= $total_loues ?></strong><span>Loués</span></div>
            </div>
            <div class="stat-card stat-purple">
                <div class="stat-icon">👥</div>
                <div><strong><?= $total_locataires ?></strong><span>Locataires</span></div>
            </div>
            <div class="stat-card stat-teal">
                <div class="stat-icon">💳</div>
                <div><strong><?= $total_paiements ?></strong><span>Paiements</span></div>
            </div>
            <div class="stat-card stat-red">
                <div class="stat-icon">⚠️</div>
                <div><strong><?= $paiements_retard ?></strong><span>En retard</span></div>
            </div>
        </div>

        <!-- Derniers biens -->
        <div class="admin-card">
            <div class="card-header">
                <h3>Derniers biens ajoutés</h3>
                <a href="ajouter_bien.php" class="btn-primary btn-sm">+ Ajouter</a>
            </div>
            <table class="admin-table">
                <thead>
                    <tr><th>Titre</th><th>Type</th><th>Ville</th><th>Prix</th><th>Statut</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($derniers_biens as $b): ?>
                    <tr>
                        <td><?= htmlspecialchars($b['titre']) ?></td>
                        <td><?= ucfirst($b['type']) ?></td>
                        <td><?= htmlspecialchars($b['ville']) ?></td>
                        <td><?= number_format($b['prix'], 0, ',', ' ') ?> €</td>
                        <td><span class="badge-statut statut-<?= $b['statut'] ?>"><?= ucfirst($b['statut']) ?></span></td>
                        <td>
                            <a href="modifier_bien.php?id=<?= $b['id'] ?>" class="btn-edit">✏️</a>
                            <a href="supprimer_bien.php?id=<?= $b['id'] ?>" class="btn-delete" onclick="return confirm('Supprimer ce bien ?')">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
