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
if ($id) {
    $conn->query("DELETE FROM equipements WHERE id = " . intval($id));
}

header("Location: EQUIP.php");
exit;
?>
