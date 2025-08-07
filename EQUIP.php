<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: LOG.php');
    exit;
}

$conn = new mysqli("localhost", "root", "", "parc_informatique");
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

$type = $_GET['type'] ?? '';
$etat = $_GET['etat'] ?? '';

$sql = "SELECT * FROM equipements WHERE 1";
if (!empty($type)) {
    $sql .= " AND type = '" . $conn->real_escape_string($type) . "'";
}
if (!empty($etat)) {
    $sql .= " AND etat = '" . $conn->real_escape_string($etat) . "'";
}
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Équipements - OCP Central</title>
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
    <h1>Liste des Équipements</h1>

    <div class="actions">
      <a href="AJOUT_EQUIP.php">➕ Ajouter un équipement</a>

      <form method="get" class="filter-form">
        <select name="type">
          <option value="">Tous les types</option>
          <option value="Ordinateur" <?= $type == 'Ordinateur' ? 'selected' : '' ?>>Ordinateur</option>
          <option value="Imprimante" <?= $type == 'Imprimante' ? 'selected' : '' ?>>Imprimante</option>
          <option value="Routeur" <?= $type == 'Routeur' ? 'selected' : '' ?>>Routeur</option>
        </select>
        <select name="etat">
          <option value="">Tous les états</option>
          <option value="Fonctionnel" <?= $etat == 'Fonctionnel' ? 'selected' : '' ?>>Fonctionnel</option>
          <option value="En panne" <?= $etat == 'En panne' ? 'selected' : '' ?>>En panne</option>
          <option value="Remplacé" <?= $etat == 'Remplacé' ? 'selected' : '' ?>>Remplacé</option>
        </select>
        <button type="submit">Filtrer</button>
      </form>
    </div>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Type</th>
          <th>État</th>
          <th>Date d'achat</th>
          <th>Localisation</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['id']) ?></td>
          <td><?= htmlspecialchars($row['nom']) ?></td>
          <td><?= htmlspecialchars($row['type']) ?></td>
          <td><?= htmlspecialchars($row['etat']) ?></td>
          <td><?= htmlspecialchars($row['date_achat']) ?></td>
          <td><?= htmlspecialchars($row['localisation']) ?></td>
          <td>
            <a class="btn edit-btn" href="MODIF_EQUIP.php?id=<?= $row['id'] ?>">Modifier</a>
            <a class="btn delete-btn" href="SUPP_EQUIP.php?id=<?= $row['id'] ?>" onclick="return confirm('Supprimer cet équipement ?')">Supprimer</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>

