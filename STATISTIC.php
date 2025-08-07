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

// Statistiques
$totalEquip = $conn->query("SELECT COUNT(*) AS total FROM equipements")->fetch_assoc()['total'];
$totalMaint = $conn->query("SELECT COUNT(*) AS total FROM maintenance")->fetch_assoc()['total'];
$totalTech = $conn->query("SELECT COUNT(*) AS total FROM utilisateurs WHERE role = 'technicien'")->fetch_assoc()['total'];

// Répartition des statuts
$statuts = ['en cours', 'terminé', 'annulé'];
$statutData = [];
foreach ($statuts as $statut) {
    $count = $conn->query("SELECT COUNT(*) AS total FROM maintenance WHERE statut = '$statut'")->fetch_assoc()['total'];
    $statutData[$statut] = $count;
}

// Équipements par type
$typeResult = $conn->query("SELECT type, COUNT(*) as total FROM equipements GROUP BY type");
$types = [];
$typeCounts = [];
while ($row = $typeResult->fetch_assoc()) {
    $types[] = $row['type'];
    $typeCounts[] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Statistiques - OCP Central</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      color: white;
      padding: 1rem;
      display: flex;
      flex-direction: column;
    }
    .sidebar h2 { text-align: center; margin-bottom: 2rem; }
    .sidebar a {
      color: white; text-decoration: none; margin: 0.6rem 0; padding: 0.5rem;
      border-radius: 6px; transition: background 0.3s;
    }
    .sidebar a:hover { background-color: #127055; }
    .main {
      flex: 1;
      background-color: #f4f4f4;
      padding: 2rem;
      overflow-y: auto;
    }
    .main h1 { color: #0f4c3a; }
    .stats {
      display: flex;
      gap: 2rem;
      margin-bottom: 2rem;
    }
    .card {
      background: white;
      padding: 1rem 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      flex: 1;
      text-align: center;
    }
    .charts {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
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
    <form action="logout.php" method="post" style="margin-top: auto;">
      <button type="submit" style="background: #e74c3c; color:white; padding: 0.6rem 1rem; border: none; border-radius: 8px;">Se déconnecter</button>
    </form>
  </div>

  <div class="main">
    <h1>Statistiques générales</h1>
    <div class="stats">
      <div class="card"><h3>Équipements</h3><p><?= $totalEquip ?></p></div>
      <div class="card"><h3>Maintenances</h3><p><?= $totalMaint ?></p></div>
      <div class="card"><h3>Techniciens</h3><p><?= $totalTech ?></p></div>
    </div>

    <div class="charts">
      <div class="card">
        <h3>Statut des maintenances</h3>
        <canvas id="statusChart"></canvas>
      </div>
      <div class="card">
        <h3>Équipements par type</h3>
        <canvas id="typeChart"></canvas>
      </div>
    </div>
  </div>

  <script>
    const statusChart = new Chart(document.getElementById('statusChart'), {
      type: 'doughnut',
      data: {
        labels: <?= json_encode(array_keys($statutData)) ?>,
        datasets: [{
          data: <?= json_encode(array_values($statutData)) ?>,
          backgroundColor: ['#0f4c3a', '#3498db', '#e67e22']
        }]
      }
    });

    const typeChart = new Chart(document.getElementById('typeChart'), {
      type: 'bar',
      data: {
        labels: <?= json_encode($types) ?>,
        datasets: [{
          label: 'Nombre',
          data: <?= json_encode($typeCounts) ?>,
          backgroundColor: '#0f4c3a'
        }]
      }
    });
  </script>
</body>
</html>
