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
    header("Location: MAIN.php");
    exit;
}

// Récupérer la maintenance existante
$stmt = $conn->prepare("SELECT * FROM maintenance WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$maintenance = $result->fetch_assoc();
if (!$maintenance) {
    header("Location: MAIN.php");
    exit;
}

// Récupérer la liste des équipements pour le select
$equipements = $conn->query("SELECT id, nom FROM equipements ORDER BY nom");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $equipement_id = $_POST['equipement_id'];
    $description = $_POST['description'];
    $date_maintenance = $_POST['date_maintenance'];
    $statut = $_POST['statut'];

    $stmt = $conn->prepare("UPDATE maintenance SET equipement_id = ?, description = ?, date_maintenance = ?, statut = ? WHERE id = ?");
    $stmt->bind_param("isssi", $equipement_id, $description, $date_maintenance, $statut, $id);
    $stmt->execute();

    header("Location: MAIN.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Modifier une maintenance</title>
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
    <h2>Modifier la maintenance</h2>

    <label>Équipement :</label>
    <select name="equipement_id" required>
      <?php while ($equip = $equipements->fetch_assoc()): ?>
        <option value="<?= $equip['id'] ?>" <?= ($equip['id'] == $maintenance['equipement_id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($equip['nom']) ?>
        </option>
      <?php endwhile; ?>
    </select>

    <label>Description :</label>
    <textarea name="description" rows="4" required><?= htmlspecialchars($maintenance['description']) ?></textarea>

    <label>Date de maintenance :</label>
    <input type="date" name="date_maintenance" value="<?= htmlspecialchars($maintenance['date_maintenance']) ?>" required />

    <label>Statut :</label>
    <select name="statut" required>
      <option value="en cours" <?= $maintenance['statut'] == 'en cours' ? 'selected' : '' ?>>En cours</option>
      <option value="terminé" <?= $maintenance['statut'] == 'terminé' ? 'selected' : '' ?>>Terminé</option>
      <option value="annulé" <?= $maintenance['statut'] == 'annulé' ? 'selected' : '' ?>>Annulé</option>
    </select>

    <button type="submit">Modifier</button>
    <a href="MAIN.php">⬅ Retour</a>
  </form>
</body>
</html>

