<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: LOG.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "parc_informatique");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    $stmt = $conn->prepare("INSERT INTO projets (nom, description, date_debut, date_fin) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nom, $description, $date_debut, $date_fin);
    $stmt->execute();

    header("Location: PROJ.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un projet</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      padding: 2rem;
    }
    form {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #0f4c3a;
    }
    label {
      display: block;
      margin-top: 1rem;
      font-weight: bold;
    }
    input, textarea {
      width: 100%;
      padding: 0.6rem;
      margin-top: 0.5rem;
    }
    button {
      margin-top: 1.5rem;
      background: #0f4c3a;
      color: white;
      padding: 0.8rem 1.2rem;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }
    a {
      display: inline-block;
      margin-top: 1rem;
      color: #0f4c3a;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <form method="POST">
    <h2>Ajouter un projet</h2>

    <label>Nom du projet :</label>
    <input type="text" name="nom" required>

    <label>Description :</label>
    <textarea name="description" rows="4" required></textarea>

    <label>Date de début :</label>
    <input type="date" name="date_debut" required>

    <label>Date de fin :</label>
    <input type="date" name="date_fin" required>

    <button type="submit">Ajouter</button>
    <a href="PROJ.php">⬅ Retour</a>
  </form>
</body>
</html>
