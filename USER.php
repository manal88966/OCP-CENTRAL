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

// Filtre possible par rôle
$role_filter = $_GET['role'] ?? '';

// Requête
$sql = "SELECT * FROM utilisateurs WHERE 1";
if ($role_filter !== '') {
    $role_safe = $conn->real_escape_string($role_filter);
    $sql .= " AND role = '$role_safe'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Utilisateurs - OCP Central</title>
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
      flex-wrap: wrap;
      gap: 1rem;
    }
    .actions a, .actions button {
      background-color: #0f4c3a;
      color: white;
      padding: 0.6rem 1rem;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      border: none;
      cursor: pointer;
    }
    form.filter-form {
      display: flex;
      gap: 1rem;
      align-items: center;
      flex-wrap: wrap;
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
      margin-right: 0.3rem;
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
    <h1>Liste des utilisateurs</h1>

    <div class="actions">
      <a href="AJOUT_USER.php">➕ Ajouter un utilisateur</a>

      <form method="get" class="filter-form">
        <label for="role">Filtrer par rôle :</label>
        <select name="role" id="role">
          <option value="">Tous</option>
          <option value="technicien" <?= $role_filter === 'technicien' ? 'selected' : '' ?>>Technicien</option>
          <option value="admin" <?= $role_filter === 'admin' ? 'selected' : '' ?>>Administrateur</option>
          <option value="utilisateur" <?= $role_filter === 'utilisateur' ? 'selected' : '' ?>>Utilisateur</option>
        </select>
        <button type="submit">Filtrer</button>
      </form>
    </div>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Email</th>
          <th>Rôle</th>
          <th>Disponible</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($user = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($user['id']) ?></td>
          <td><?= htmlspecialchars($user['nom']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><?= htmlspecialchars($user['role']) ?></td>
          <td><?= $user['disponible'] ? 'Oui' : 'Non' ?></td>
          <td>
            <a class="btn edit-btn" href="MODIF_USER.php?id=<?= $user['id'] ?>">Modifier</a>
            <a class="btn delete-btn" href="SUPP_USER.php?id=<?= $user['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
