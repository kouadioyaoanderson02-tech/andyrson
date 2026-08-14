<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../connexion.php"); exit;
}
require '../includes/connexion_db.php';

$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO paiements (locataire_id, bien_id, montant, date_paiement, statut, mois) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$_POST['locataire_id'], $_POST['bien_id'], $_POST['montant'],
                    $_POST['date_paiement'], $_POST['statut'], $_POST['mois']]);
    $succes = "Paiement enregistré !";
}

$paiements  = $pdo->query("SELECT p.*, CONCAT(l.prenom,' ',l.nom) as locataire_nom, b.titre as bien_titre
                            FROM paiements p
                            JOIN locataires l ON p.locataire_id = l.id
                            JOIN biens b ON p.bien_id = b.id
                            ORDER BY p.date_paiement DESC")->fetchAll();
$locataires = $pdo->query("SELECT l.id, CONCAT(l.prenom,' ',l.nom) as nom, l.bien_id FROM locataires l")->fetchAll();
$biens      = $pdo->query("SELECT id, titre FROM biens")->fetchAll();
?>
<?php include '../includes/header.php'; ?>

<div class="admin-layout">
    <aside class="sidebar">
        <div class="sidebar-title">⚙️ Administration</div>
        <nav class="sidebar-nav">
            <a href="dashboard.php">📊 Dashboard</a>
            <a href="biens.php">🏠 Biens</a>
            <a href="ajouter_bien.php">➕ Ajouter un bien</a>
            <a href="locataires.php">👥 Locataires</a>
            <a href="paiements.php" class="active">💳 Paiements</a>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="admin-header"><h1>Paiements</h1></div>
        <?php if ($succes): ?><div class="alert alert-success"><?= $succes ?></div><?php endif; ?>

        <div class="admin-card">
            <h3>Enregistrer un paiement</h3>
            <form method="POST" class="admin-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Locataire</label>
                        <select name="locataire_id" required>
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($locataires as $l): ?>
                                <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Bien</label>
                        <select name="bien_id" required>
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($biens as $b): ?>
                                <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['titre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Montant (€)</label><input type="number" name="montant" required></div>
                    <div class="form-group"><label>Date</label><input type="date" name="date_paiement" required></div>
                    <div class="form-group"><label>Mois concerné</label><input type="text" name="mois" placeholder="Janvier 2024"></div>
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="statut">
                            <option value="payé">Payé</option>
                            <option value="en attente">En attente</option>
                            <option value="retard">Retard</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Enregistrer</button>
            </form>
        </div>

        <div class="admin-card">
            <h3>Historique des paiements</h3>
            <table class="admin-table">
                <thead>
                    <tr><th>Locataire</th><th>Bien</th><th>Mois</th><th>Montant</th><th>Date</th><th>Statut</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($paiements as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['locataire_nom']) ?></td>
                        <td><?= htmlspecialchars($p['bien_titre']) ?></td>
                        <td><?= htmlspecialchars($p['mois']) ?></td>
                        <td><?= number_format($p['montant'], 0, ',', ' ') ?> €</td>
                        <td><?= $p['date_paiement'] ?></td>
                        <td><span class="badge-statut statut-<?= str_replace(' ','-',$p['statut']) ?>"><?= ucfirst($p['statut']) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
