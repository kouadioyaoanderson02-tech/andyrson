<?php
session_start();
require '../includes/connexion_db.php';

$biens = $pdo->query("SELECT * FROM biens WHERE statut = 'disponible' ORDER BY created_at DESC LIMIT 6")->fetchAll();
$total_biens = $pdo->query("SELECT COUNT(*) FROM biens")->fetchColumn();
$total_dispo = $pdo->query("SELECT COUNT(*) FROM biens WHERE statut='disponible'")->fetchColumn();
$total_loues = $pdo->query("SELECT COUNT(*) FROM biens WHERE statut='loue'")->fetchColumn();
?>
<?php include '../includes/header.php'; ?>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <h1>Trouvez le bien<br><span class="highlight">idéal</span> pour vous</h1>
        <p>Appartements, maisons, villas — location et vente partout en France</p>
        <form action="rechercher.php" method="GET" class="search-form">
            <input type="text" name="q" placeholder="🔍 Ville, type de bien...">
            <select name="type">
                <option value="">Tous les types</option>
                <option value="appartement">Appartement</option>
                <option value="maison">Maison</option>
                <option value="villa">Villa</option>
                <option value="bureau">Bureau</option>
                <option value="terrain">Terrain</option>
            </select>
            <button type="submit" class="btn-primary">Rechercher</button>
        </form>
    </div>
    <div class="hero-image">🏠🏢🏡</div>
</section>

<!-- STATS -->
<section class="stats">
    <div class="stat-item"><strong><?= $total_biens ?></strong><span>Biens au total</span></div>
    <div class="stat-item"><strong><?= $total_dispo ?></strong><span>Disponibles</span></div>
    <div class="stat-item"><strong><?= $total_loues ?></strong><span>Loués</span></div>
    <div class="stat-item"><strong>100%</strong><span>Confiance</span></div>
</section>

<!-- BIENS RÉCENTS -->
<section class="biens-section">
    <div class="container">
        <h2 class="section-title">Biens disponibles</h2>
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
                <p class="empty-msg">Aucun bien disponible pour le moment.</p>
            <?php endif; ?>
        </div>
        <div class="text-center mt-2">
            <a href="rechercher.php" class="btn-primary">Voir tous les biens</a>
        </div>
    </div>
</section>

<!-- SERVICES -->
<section class="services-section">
    <div class="container">
        <h2 class="section-title">Nos services</h2>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">🏠</div>
                <h3>Location</h3>
                <p>Trouvez votre logement idéal parmi nos offres de location</p>
            </div>
            <div class="service-card">
                <div class="service-icon">💰</div>
                <h3>Vente</h3>
                <p>Achetez ou vendez votre bien en toute sécurité</p>
            </div>
            <div class="service-card">
                <div class="service-icon">📋</div>
                <h3>Gestion</h3>
                <p>Confiez-nous la gestion de vos biens immobiliers</p>
            </div>
            <div class="service-card">
                <div class="service-icon">🔑</div>
                <h3>Conseil</h3>
                <p>Nos experts vous accompagnent dans vos projets</p>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
