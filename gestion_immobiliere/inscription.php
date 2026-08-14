<?php
session_start();
require 'includes/connexion_db.php';

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom    = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email  = trim($_POST['email']);
    $tel    = trim($_POST['telephone']);
    $mdp    = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

    $check = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $check->execute([$email]);

    if ($check->fetch()) {
        $erreur = "Cet email est déjà utilisé.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, telephone, mot_de_passe) VALUES (?,?,?,?,?)");
        $stmt->execute([$nom, $prenom, $email, $tel, $mdp]);
        $succes = "Compte créé avec succès !";
    }
}
?>
<?php include 'includes/header.php'; ?>

<div class="auth-page">
    <div class="auth-card">
        <h2>📝 Créer un compte</h2>
        <?php if ($erreur): ?><div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>
        <?php if ($succes): ?><div class="alert alert-success"><?= $succes ?> <a href="connexion.php">Se connecter</a></div><?php endif; ?>
        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" required placeholder="Dupont">
                </div>
                <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" name="prenom" required placeholder="Jean">
                </div>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="votre@email.com">
            </div>
            <div class="form-group">
                <label>Téléphone</label>
                <input type="tel" name="telephone" placeholder="+33 6 00 00 00 00">
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="mot_de_passe" required placeholder="Min. 6 caractères" minlength="6">
            </div>
            <button type="submit" class="btn-primary btn-full">S'inscrire</button>
        </form>
        <p class="auth-link">Déjà inscrit ? <a href="connexion.php">Se connecter</a></p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
