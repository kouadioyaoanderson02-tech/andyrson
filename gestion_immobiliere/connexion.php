<?php
session_start();
require 'includes/connexion_db.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $mdp   = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($mdp, $user['mot_de_passe'])) {
        $_SESSION['user'] = $user;
        if ($user['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: client/accueil.php");
        }
        exit;
    } else {
        $erreur = "Email ou mot de passe incorrect.";
    }
}
?>
<?php include 'includes/header.php'; ?>

<div class="auth-page">
    <div class="auth-card">
        <h2>🔐 Connexion</h2>
        <?php if ($erreur): ?>
            <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="votre@email.com">
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="mot_de_passe" required placeholder="••••••">
            </div>
            <button type="submit" class="btn-primary btn-full">Se connecter</button>
        </form>
        <p class="auth-link">Pas de compte ? <a href="inscription.php">S'inscrire</a></p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
