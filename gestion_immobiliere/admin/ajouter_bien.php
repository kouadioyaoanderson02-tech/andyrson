<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../connexion.php"); exit;
}
require '../includes/connexion_db.php';

$succes = $erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre    = trim($_POST['titre']);
    $type     = $_POST['type'];
    $statut   = $_POST['statut'];
    $prix     = $_POST['prix'];
    $surface  = $_POST['surface'] ?: null;
    $pieces   = $_POST['nb_pieces'] ?: null;
    $adresse  = trim($_POST['adresse']);
    $ville    = trim($_POST['ville']);
    $desc     = trim($_POST['description']);
    $photo    = null;

    if (!empty($_FILES['photo']['name'])) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/photos_biens/$photo");
    }

    $stmt = $pdo->prepare("INSERT INTO biens (titre, type, statut, prix, surface, nb_pieces, adresse, ville, description, photo) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([$titre, $type, $statut, $prix, $surface, $pieces, $adresse, $ville, $desc, $photo]);
    $succes = "Bien ajouté avec succès !";
}
?>
<?php include '../includes/header.php'; ?>

<div class="admin-layout">
    <aside class="sidebar">
        <div class="sidebar-title">⚙️ Administration</div>
        <nav class="sidebar-nav">
            <a href="dashboard.php">📊 Dashboard</a>
            <a href="biens.php">🏠 Biens</a>
            <a href="ajouter_bien.php" class="active">➕ Ajouter un bien</a>
            <a href="locataires.php">👥 Locataires</a>
            <a href="paiements.php">💳 Paiements</a>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="admin-header"><h1>Ajouter un bien</h1></div>

        <?php if ($succes): ?><div class="alert alert-success"><?= $succes ?></div><?php endif; ?>
        <?php if ($erreur): ?><div class="alert alert-error"><?= $erreur ?></div><?php endif; ?>

        <div class="admin-card">
            <form method="POST" enctype="multipart/form-data" class="admin-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Titre *</label>
                        <input type="text" name="titre" required placeholder="Bel appartement T3...">
                    </div>
                    <div class="form-group">
                        <label>Type *</label>
                        <select name="type" required>
                            <option value="appartement">Appartement</option>
                            <option value="maison">Maison</option>
                            <option value="villa">Villa</option>
                            <option value="bureau">Bureau</option>
                            <option value="terrain">Terrain</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Prix (€) *</label>
                        <input type="number" name="prix" required placeholder="850">
                    </div>
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="statut">
                            <option value="disponible">Disponible</option>
                            <option value="loue">Loué</option>
                            <option value="vendu">Vendu</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Surface (m²)</label>
                        <input type="number" name="surface" placeholder="65">
                    </div>
                    <div class="form-group">
                        <label>Nombre de pièces</label>
                        <input type="number" name="nb_pieces" placeholder="3">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Adresse</label>
                        <input type="text" name="adresse" placeholder="12 rue de la Paix">
                    </div>
                    <div class="form-group">
                        <label>Ville</label>
                        <input type="text" name="ville" placeholder="Paris">
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4" placeholder="Décrivez le bien..."></textarea>
                </div>
                <div class="form-group">
                    <label>Photo</label>
                    <input type="file" name="photo" accept="image/*">
                </div>
                <button type="submit" class="btn-primary">Ajouter le bien</button>
            </form>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
