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

$res1 = $conn->query("SELECT COUNT(*) AS total FROM equipements");
$totalEquip = $res1->fetch_assoc()['total'];

$res2 = $conn->query("SELECT COUNT(*) AS total FROM maintenance WHERE statut = 'en cours'");
$intervEnCours = $res2->fetch_assoc()['total'];

$res3 = $conn->query("SELECT COUNT(*) AS total FROM utilisateurs WHERE role = 'technicien' AND disponible = 1");
$techDispo = $res3->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tableau de Bord - OCP Central</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .header h1 {
      margin: 0;
      color: #0f4c3a;
    }

    .logout-btn {
      background-color: #e74c3c;
      border: none;
      color: white;
      padding: 0.6rem 1rem;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
    }

    .card {
      background: #fff;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    canvas {
      max-width: 100%;
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
    <form action="LOGOU.php" method="post" style="margin-top: auto;">
      <button class="logout-btn" type="submit">Se déconnecter</button>
    </form>
  </div>

  <div class="main">
    <div class="header">
      <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h1>
    </div>

    <div class="cards">
      <div class="card">
        <h3>Projets</h3>
        <canvas id="projectsChart"></canvas>
      </div>
      <div class="card">
        <h3>Revenus</h3>
        <canvas id="incomeChart"></canvas>
      </div>
      <div class="card">
        <h3>Statistiques rapides</h3>
        <ul>
          <li>Nombre total d'équipements : <?php echo $totalEquip; ?></li>
          <li>Nombre d'interventions en cours : <?php echo $intervEnCours; ?></li>
          <li>Techniciens disponibles : <?php echo $techDispo; ?></li>
        </ul>
      </div>
    </div>
  </div>

  <script>
    const ctx1 = document.getElementById('projectsChart').getContext('2d');
    const projectsChart = new Chart(ctx1, {
      type: 'bar',
      data: {
        labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven'],
        datasets: [{
          label: 'Projets',
          data: [12, 19, 3, 5, 2],
          backgroundColor: '#0f4c3a'
        }]
      }
    });

    const ctx2 = document.getElementById('incomeChart').getContext('2d');
    const incomeChart = new Chart(ctx2, {
      type: 'line',
      data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai'],
        datasets: [{
          label: 'Revenus',
          data: [1200, 1900, 3000, 5000, 2300],
          backgroundColor: 'rgba(15, 76, 58, 0.2)',
          borderColor: '#0f4c3a',
          borderWidth: 2,
          fill: true
        }]
      }
    });
  </script>
</body>
</html>
