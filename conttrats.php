<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

// Récupération des contrats
$stmt = $pdo->query("SELECT * FROM contrats ORDER BY date_debut DESC");
$contrats = $stmt->fetchAll();
?>

<h1>Contrats</h1>
<table>
  <thead>
    <tr><th>ID</th><th>Nom contrat</th><th>Date début</th><th>Date fin</th></tr>
  </thead>
  <tbody>
    <?php foreach ($contrats as $contrat): ?>
    <tr>
      <td><?= htmlspecialchars($contrat['id']) ?></td>
      <td><?= htmlspecialchars($contrat['nom']) ?></td>
      <td><?= htmlspecialchars($contrat['date_debut']) ?></td>
      <td><?= htmlspecialchars($contrat['date_fin']) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php require_once '../includes/footer.php'; ?>
