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

// Récupération des projets
$sql = "SELECT * FROM projets ORDER BY date_debut DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Projets - OCP Central</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, sans-serif;
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
      text-align: center;
      margin-bottom: 2rem;
    }

    .sidebar a {
      color: #fff;
      text-decoration: none;
      margin: 0.6rem 0;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      transition: background 0.3s;
    }

    .sidebar a:hover {
      background-color: #127055;
    }

    .main {
      flex: 1;
      padding: 2rem;
      background-color: #f4f4f4;
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

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
    <h1>Liste des Projets</h1>

    <div class="actions">
      <a href="AJOUT_PROJ.php">➕ Ajouter un projet</a>
    </div>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Description</th>
          <th>Date Début</th>
          <th>Date Fin</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['id']) ?></td>
              <td><?= htmlspecialchars($row['nom']) ?></td>
              <td><?= htmlspecialchars($row['description']) ?></td>
              <td><?= htmlspecialchars($row['date_debut']) ?></td>
              <td><?= htmlspecialchars($row['date_fin']) ?></td>
              <td>
                <a class="btn edit-btn" href="MODIF_PROJ.php?id=<?= $row['id'] ?>">Modifier</a>
                <a class="btn delete-btn" href="SUPP_PROJ.php?id=<?= $row['id'] ?>" onclick="return confirm('Supprimer ce projet ?')">Supprimer</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="6">Aucun projet trouvé.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>



