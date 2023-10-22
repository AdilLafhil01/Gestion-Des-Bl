<?php

$servername = "localhost:90"; // Adresse du serveur MySQL (généralement 'localhost' en développement)
$username = "localhost"; // Votre nom d'utilisateur MySQL
$password = "ouadie123"; // Votre mot de passe MySQL
$database = "gestionbl"; // Le nom de votre base de données

// Créez une connexion MySQL en utilisant les informations ci-dessus
$mysqli = new mysqli($servername, $username, $password, $database);

// Vérifiez la connexion
if ($mysqli->connect_error) {
    die("Échec de la connexion à la base de données : " . $mysqli->connect_error);
}

// Définissez le jeu de caractères de la connexion (facultatif, mais recommandé)
$mysqli->set_charset("utf8");

?>
