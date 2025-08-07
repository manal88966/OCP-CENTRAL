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

// Récupérer la liste des équipements pour le select
$equipements = $conn->query("SELECT id, nom FROM equipements ORDER BY nom");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $equipement_id = $_POST['equipement_id'];
    $description = $_POST['description'];
    $date_maintenance = $_POST['date_maintenance'];
    $statut = $_POST['statut'];

    $stmt = $conn->prepare("INSERT INTO maintenance (equipement_id, description, date_maintenance, statut) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $equipement_id, $description, $date_maintenance, $statut);
    $stmt->execute();

    header("Location: MAIN.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Ajouter une maintenance</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 2rem; }
    form { max-width: 500px; background: white; padding: 2rem; margin: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    h2 { text-align: center; color: #0f4c3a; }
    label { display: block; margin-top: 1rem; }
    input, select, textarea { width: 100%; padding: 0.6rem; margin-top: 0.5rem; }
    button { margin-top: 1.5rem; background: #0f4c3a; color: white; padding: 0.8rem; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
    a { display: inline-block; margin-top: 1rem; color: #0f4c3a; text-decoration: none; }
  </style>
</head>
<body>
  <form method="POST">
    <h2>Ajouter une maintenance</h2>

    <label>Équipement :</label>
    <select name="equipement_id" required>
      <option value="">-- Choisir un équipement --</option>
      <?php while ($equip = $equipements->fetch_assoc()): ?>
        <option value="<?= $equip['id'] ?>"><?= htmlspecialchars($equip['nom']) ?></option>
      <?php endwhile; ?>
    </select>

    <label>Description :</label>
    <textarea name="description" rows="4" required></textarea>

    <label>Date de maintenance :</label>
    <input type="date" name="date_maintenance" required />

    <label>Statut :</label>
    <select name="statut" required>
      <option value="en cours">En cours</option>
      <option value="terminé">Terminé</option>
      <option value="annulé">Annulé</option>
    </select>

    <button type="submit">Ajouter</button>
    <a href="MAIN.php">⬅ Retour</a>
  </form>
</body>
</html>

