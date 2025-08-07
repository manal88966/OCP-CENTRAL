<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: LOG.php');
    exit;
}

$conn = new mysqli("localhost", "root", "", "parc_informatique");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: USER.php");
    exit;
}

// Récupérer utilisateur
$stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

if (!$user) {
    header("Location: USER.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $disponible = isset($_POST['disponible']) ? 1 : 0;

    // Mise à jour
    $stmt = $conn->prepare("UPDATE utilisateurs SET nom=?, email=?, role=?, disponible=? WHERE id=?");
    $stmt->bind_param("sssii", $nom, $email, $role, $disponible, $id);
    $stmt->execute();

    // Mise à jour mot de passe si rempli
    if (!empty($_POST['password'])) {
        $new_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE utilisateurs SET password=? WHERE id=?");
        $stmt->bind_param("si", $new_pass, $id);
        $stmt->execute();
    }

    header("Location: USER.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Modifier utilisateur - OCP Central</title>
<style>
body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 2rem; }
form { max-width: 400px; background: white; padding: 2rem; margin: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
h2 { text-align: center; color: #0f4c3a; }
label { display: block; margin-top: 1rem; }
input, select { width: 100%; padding: 0.6rem; margin-top: 0.5rem; }
button { margin-top: 1.5rem; background: #0f4c3a; color: white; padding: 0.8rem; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
a { display: inline-block; margin-top: 1rem; color: #0f4c3a; text-decoration: none; }
</style>
</head>
<body>
<form method="POST">
  <h2>Modifier un utilisateur</h2>

  <label>Nom :</label>
  <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required />

  <label>Email :</label>
  <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required />

  <label>Mot de passe (laisser vide pour ne pas changer) :</label>
  <input type="password" name="password" />

  <label>Rôle :</label>
  <select name="role" required>
    <option value="utilisateur" <?= $user['role'] === 'utilisateur' ? 'selected' : '' ?>>Utilisateur</option>
    <option value="technicien" <?= $user['role'] === 'technicien' ? 'selected' : '' ?>>Technicien</option>
    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
  </select>

  <label>
    <input type="checkbox" name="disponible" <?= $user['disponible'] ? 'checked' : '' ?> />
    Disponible
  </label>

  <button type="submit">Enregistrer</button>
  <a href="USER.php">⬅ Retour</a>
</form>
</body>
</html>
