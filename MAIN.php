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

$statut = $_GET['statut'] ?? '';

$sql = "SELECT m.*, e.nom AS equip_nom FROM maintenance m LEFT JOIN equipements e ON m.equipement_id = e.id WHERE 1";
if (!empty($statut)) {
    $sql .= " AND m.statut = '" . $conn->real_escape_string($statut) . "'";
}
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Maintenances - OCP Central</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      height: 100vh;
    }
    .sidebar {
      width: 220px;
      background-color: #0f4c3a;
      color: #fff;
      display: flex;
      flex-direction: column;
      padding: 1rem;
    }
    .sidebar h2 {
      margin-bottom: 2rem;
      font-size: 1.5rem;
      text-align: center;
    }
    .sidebar a {
      color: #fff;
      text-decoration: none;
      margin: 0.8rem 0;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      transition: background 0.3s;
    }
    .sidebar a:hover {
      background-color: #127055;
    }
    .main {
      flex: 1;
      background-color: #f4f4f4;
      padding: 2rem;
      overflow-y: auto;
    }
    .main h1 {
      color: #0f4c3a;
    }
    .actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }
    .actions a {
      background-color: #0f4c3a;
      color: white;
      padding: 0.6rem 1rem;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
    }
    form.filter-form {
      display: flex;
      gap: 1rem;
    }
    form.filter-form select {
      padding: 0.5rem;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    th, td {
      padding: 1rem;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #0f4c3a;
      color: white;
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    .btn {
      padding: 0.4rem 0.8rem;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      text-decoration: none;
      font-size: 0.9rem;
    }
    .edit-btn {
      background-color: #3498db;
      color: white;
    }
    .delete-btn {
      background-color: #e74c3c;
      color: white;
    }
    .logout-btn {
      background-color: #e74c3c;
      border: none;
      color: white;
      padding: 0.6rem 1rem;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      margin-top: auto;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>OCP Central</h2>
    <a href="DASH.php">Dashboard</a>
    <a href="EQUIP.php">Équipements</a>
    <a href="MAIN.php">Maintenances</a>
    <a href="USER.php">Utilisateurs</a>
    <a href="STATISTIC.php">Statistiques</a>
    <a href="PROJ.php">Projets</a>
    <a href="PROF.php">Profil</a>
    <form action="logout.php" method="post">
      <button class="logout-btn" type="submit">Se déconnecter</button>
    </form>
  </div>

  <div class="main">
    <h1>Liste des Maintenances</h1>

    <div class="actions">
      <a href="AJOUT_MAIN.php">➕ Ajouter une maintenance</a>

      <form method="get" class="filter-form">
        <select name="statut">
          <option value="">Tous les statuts</option>
          <option value="en cours" <?= $statut == 'en cours' ? 'selected' : '' ?>>En cours</option>
          <option value="terminé" <?= $statut == 'terminé' ? 'selected' : '' ?>>Terminé</option>
          <option value="annulé" <?= $statut == 'annulé' ? 'selected' : '' ?>>Annulé</option>
        </select>
        <button type="submit">Filtrer</button>
      </form>
    </div>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Équipement</th>
          <th>Description</th>
          <th>Date début</th>
          <th>Date fin</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['id']) ?></td>
          <td><?= htmlspecialchars($row['equip_nom']) ?></td>
          <td><?= htmlspecialchars($row['description']) ?></td>
          <td><?= htmlspecialchars($row['date_maintenance']) ?></td>
<td>-</td> <!-- si tu n’as pas de colonne date_fin -->

          <td><?= htmlspecialchars($row['statut']) ?></td>
          <td>
            <a class="btn edit-btn" href="MODIF_MAIN.php?id=<?= $row['id'] ?>">Modifier</a>
            <a class="btn delete-btn" href="SUPP_MAIN.php?id=<?= $row['id'] ?>" onclick="return confirm('Supprimer cette maintenance ?')">Supprimer</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>

