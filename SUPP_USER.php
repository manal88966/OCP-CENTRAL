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
    header("Location: USER.php");
    exit;
}

$stmt = $conn->prepare("DELETE FROM utilisateurs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: USER.php");
exit;
