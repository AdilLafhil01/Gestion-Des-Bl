<?php

$servername = "localhost"; // Adresse du serveur MySQL (généralement 'localhost' en développement)
$username = "root"; // Votre nom d'utilisateur MySQL
$password = "adil1234"; // Votre mot de passe MySQL
$database = "gestion_bl"; // Le nom de votre base de données
 global $mysqli;
// Créez une connexion MySQL en utilisant les informations ci-dessus
 $mysqli = new mysqli($servername, $username, $password, $database);

// Vérifiez la connexion
if ($mysqli->connect_error) {
    die("Échec de la connexion à la base de données : " . $mysqli->connect_error);
}

// Définissez le jeu de caractères de la connexion (facultatif, mais recommandé)
return $mysqli->set_charset("utf8");

?>
