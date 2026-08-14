<?php
session_start();
require '../includes/connexion_db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM biens WHERE id = ?");
$stmt->execute([$id]);
$bien = $stmt->fetch();

if (!$bien) { header("Location: rechercher.php"); exit; }
?>
<?php include '../includes/header.php'; ?>

<div class="container">
    <a href="rechercher.php" class="btn-back">← Retour</a>

    <div class="detail-grid">
        <div class="detail-img">
            <?php if ($bien['photo']): ?>
                <img src="/andyrson/gestion_immobiliere/uploads/photos_biens/<?= htmlspecialchars($bien['photo']) ?>" alt="bien">
            <?php else: ?>
                <div class="detail-img-placeholder">🏠</div>
            <?php endif; ?>
        </div>

        <div class="detail-info">
            <span class="badge-type badge-lg"><?= ucfirst($bien['type']) ?></span>
            <h1><?= htmlspecialchars($bien['titre']) ?></h1>
            <p class="detail-ville">📍 <?= htmlspecialchars($bien['adresse']) ?>, <?= htmlspecialchars($bien['ville']) ?></p>

            <div class="detail-stats">
                <?php if ($bien['surface']): ?>
                    <div class="detail-stat"><span>📐</span><strong><?= $bien['surface'] ?> m²</strong><small>Surface</small></div>
                <?php endif; ?>
                <?php if ($bien['nb_pieces']): ?>
                    <div class="detail-stat"><span>🚪</span><strong><?= $bien['nb_pieces'] ?></strong><small>Pièces</small></div>
                <?php endif; ?>
                <div class="detail-stat">
                    <span>📊</span>
                    <strong class="statut-<?= $bien['statut'] ?>"><?= ucfirst($bien['statut']) ?></strong>
                    <small>Statut</small>
                </div>
            </div>

            <div class="detail-prix"><?= number_format($bien['prix'], 0, ',', ' ') ?> €</div>

            <div class="detail-desc">
                <h3>Description</h3>
                <p><?= nl2br(htmlspecialchars($bien['description'])) ?></p>
            </div>

            <?php if ($bien['statut'] === 'disponible'): ?>
                <a href="../connexion.php" class="btn-primary btn-lg">Contacter l'agence</a>
            <?php else: ?>
                <span class="badge-indispo">Non disponible</span>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
