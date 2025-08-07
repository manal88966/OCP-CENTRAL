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
    $type = $_POST['type'];
    $etat = $_POST['etat'];
    $date_achat = $_POST['date_achat'];
    $localisation = $_POST['localisation'];

    $stmt = $conn->prepare("INSERT INTO equipements (nom, type, etat, date_achat, localisation) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nom, $type, $etat, $date_achat, $localisation);
    $stmt->execute();

    header("Location: EQUIP.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Ajouter un équipement</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 2rem; background: #f4f4f4; }
    form { max-width: 500px; margin: auto; background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    h2 { text-align: center; color: #0f4c3a; }
    label { display: block; margin-top: 1rem; }
    input, select { width: 100%; padding: 0.6rem; margin-top: 0.5rem; }
    button { margin-top: 1.5rem; padding: 0.8rem 1.2rem; background: #0f4c3a; color: white; border: none; border-radius: 6px; cursor: pointer; }
    a { display: inline-block; margin-top: 1rem; color: #0f4c3a; text-decoration: none; }
  </style>
</head>
<body>

  <form method="POST">
    <h2>Ajouter un équipement</h2>
    <label>Nom :</label>
    <input type="text" name="nom" required />

    <label>Type :</label>
    <select name="type" required>
      <option>Ordinateur</option>
      <option>Imprimante</option>
      <option>Routeur</option>
    </select>

    <label>État :</label>
    <select name="etat" required>
      <option>Fonctionnel</option>
      <option>En panne</option>
      <option>Remplacé</option>
    </select>

    <label>Date d'achat :</label>
    <input type="date" name="date_achat" required />

    <label>Localisation :</label>
    <input type="text" name="localisation" required />

    <button type="submit">Ajouter</button>
    <a href="EQUIP.php">⬅ Retour</a>
  </form>

</body>
</html>
