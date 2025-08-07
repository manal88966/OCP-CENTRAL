<?php
session_start();

// Vider les variables de session
$_SESSION = [];

// Détruire la session
session_destroy();

// Redirection vers la page de connexion
header("Location: LOG.php"); // Assure-toi que le nom du fichier de login est bien LOG.php
exit;
?>
