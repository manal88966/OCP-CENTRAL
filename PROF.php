<?p<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['username'])) {
    header('Location: LOG.php');
    exit;
}

// Connexion à la base
$conn = new mysqli("localhost", "root", "", "parc_informatique");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Récupère le nom d'utilisateur depuis la session
$nom = $_SESSION['username'];

// Requête préparée
$stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE nom = ?");
$stmt->bind_param("s", $nom);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Profil - OCP Central</title>
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
      margin-bottom: 2rem;
    }

    .profile-card {
      background: white;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      max-width: 500px;
    }

    .profile-card h2 {
      margin-top: 0;
      color: #0f4c3a;
    }

    .profile-card p {
      margin: 0.6rem 0;
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
    <form action="LOGOU.php" method="post">
      <button class="logout-btn" type="submit">Se déconnecter</button>
    </form>
  </div>

  <div class="main">
    <h1>Mon Profil</h1>
    <div class="profile-card">
      <?php if ($user): ?>
        <h2><?= htmlspecialchars($user['nom']) ?></h2>
        <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user['nom']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Rôle :</strong> <?= htmlspecialchars($user['role']) ?></p>
        <p><strong>Disponible :</strong> <?= $user['disponible'] ? 'Oui' : 'Non' ?></p>
      <?php else: ?>
        <p>Aucun utilisateur trouvé.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>




