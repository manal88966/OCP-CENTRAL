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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $disponible = isset($_POST['disponible']) ? 1 : 0;
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // hasher le mdp

    $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, email, role, disponible, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $nom, $email, $role, $disponible, $password);
    $stmt->execute();

    header("Location: USER.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Ajouter un utilisateur - OCP Central</title>
<style>
/* même style simple que précédemment */
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
  <h2>Ajouter un utilisateur</h2>

  <label>Nom :</label>
  <input type="text" name="nom" required />

  <label>Email :</label>
  <input type="email" name="email" required />

  <label>Mot de passe :</label>
  <input type="password" name="password" required />

  <label>Rôle :</label>
  <select name="role" required>
    <option value="utilisateur">Utilisateur</option>
    <option value="technicien">Technicien</option>
    <option value="admin">Administrateur</option>
  </select>

  <label>
    <input type="checkbox" name="disponible" />
    Disponible
  </label>

  <button type="submit">Ajouter</button>
  <a href="USER.php">⬅ Retour</a>
</form>
</body>
</html>
