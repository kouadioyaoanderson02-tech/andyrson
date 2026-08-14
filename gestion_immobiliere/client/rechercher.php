<?php
session_start();
require '../includes/connexion_db.php';

$q      = trim($_GET['q'] ?? '');
$type   = $_GET['type'] ?? '';
$prix_max = $_GET['prix_max'] ?? '';

$sql = "SELECT * FROM biens WHERE statut = 'disponible'";
$params = [];

if ($q) {
    $sql .= " AND (titre LIKE ? OR ville LIKE ? OR adresse LIKE ?)";
    $params = array_merge($params, ["%$q%", "%$q%", "%$q%"]);
}
if ($type) {
    $sql .= " AND type = ?";
    $params[] = $type;
}
if ($prix_max) {
    $sql .= " AND prix <= ?";
    $params[] = $prix_max;
}
$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$biens = $stmt->fetchAll();
?>
<?php include '../includes/header.php'; ?>

<div class="container">
    <h2 class="section-title">Rechercher un bien</h2>

    <form method="GET" class="search-filters">
        <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="🔍 Ville, titre...">
        <select name="type">
            <option value="">Tous les types</option>
            <?php foreach (['appartement','maison','villa','bureau','terrain'] as $t): ?>
                <option value="<?= $t ?>" <?= $type === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="prix_max" value="<?= htmlspecialchars($prix_max) ?>" placeholder="Prix max (€)">
        <button type="submit" class="btn-primary">Filtrer</button>
        <a href="rechercher.php" class="btn-outline-sm">Réinitialiser</a>
    </form>

    <p class="result-count"><?= count($biens) ?> bien(s) trouvé(s)</p>

    <div class="biens-grid">
        <?php foreach ($biens as $bien): ?>
        <div class="bien-card">
            <div class="bien-img-wrap">
                <?php if ($bien['photo']): ?>
                    <img src="/andyrson/gestion_immobiliere/uploads/photos_biens/<?= htmlspecialchars($bien['photo']) ?>" alt="bien">
                <?php else: ?>
                    <div class="bien-img-placeholder">🏠</div>
                <?php endif; ?>
                <span class="badge-type"><?= ucfirst($bien['type']) ?></span>
            </div>
            <div class="bien-body">
                <h3><?= htmlspecialchars($bien['titre']) ?></h3>
                <p class="bien-ville">📍 <?= htmlspecialchars($bien['ville']) ?></p>
                <div class="bien-details">
                    <?php if ($bien['surface']): ?><span>📐 <?= $bien['surface'] ?> m²</span><?php endif; ?>
                    <?php if ($bien['nb_pieces']): ?><span>🚪 <?= $bien['nb_pieces'] ?> pièces</span><?php endif; ?>
                </div>
                <div class="bien-footer">
                    <strong class="bien-prix"><?= number_format($bien['prix'], 0, ',', ' ') ?> €</strong>
                    <a href="details_bien.php?id=<?= $bien['id'] ?>" class="btn-primary btn-sm">Voir</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if (empty($biens)): ?>
            <p class="empty-msg">Aucun bien trouvé.</p>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
