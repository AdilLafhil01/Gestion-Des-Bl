




<?php
include_once '../../dao/dao.php';

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === "ajouter") {
            // Ajout d'un utilisateur
            $username1 = $_POST['_username'];
            $password1 = $_POST['password'];
            $role1 = $_POST['role'];
            $service1 = $_POST['Service'];

            $username1 = mysqli_real_escape_string($mysqli, $username1);
            $password1 = mysqli_real_escape_string($mysqli, $password1);
            $role1 = mysqli_real_escape_string($mysqli, $role1);
            $service1 = mysqli_real_escape_string($mysqli, $service1);

            $sql = "INSERT INTO utilisateur (Nom_utilisateur, password, Role, Service) VALUES (?, ?, ?, ?)";

            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("ssss", $username1, $password1, $role1, $service1);

                if ($stmt->execute()) {
                    echo "Utilisateur ajouté avec succès";
                } else {
                    echo "Erreur lors de l'ajout de l'utilisateur : " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Erreur lors de la préparation de la requête : " . $mysqli->error;
            }
        } elseif ($_POST['action'] === "modifier") {
            // Modification d'un utilisateur
            $id_utilisateur = $_POST['matricule'];
            $username1 = $_POST['_username'];
            $password1 = $_POST['password'];
            $role1 = $_POST['role'];
            $service1 = $_POST['Service'];

            $id_utilisateur = mysqli_real_escape_string($mysqli, $id_utilisateur);
            $username1 = mysqli_real_escape_string($mysqli, $username1);
            $password1 = mysqli_real_escape_string($mysqli, $password1);
            $role1 = mysqli_real_escape_string($mysqli, $role1);
            $service1 = mysqli_real_escape_string($mysqli, $service1);

            $sql = "UPDATE utilisateur SET Nom_utilisateur = ?, password = ?, Role = ?, Service = ? WHERE ID_utilisateur = ?";

            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("ssssi", $username1, $password1, $role1, $service1, $id_utilisateur);

                if ($stmt->execute()) {
                    echo "Utilisateur mis à jour avec succès";
                } else {
                    echo "Erreur lors de la mise à jour de l'utilisateur : " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Erreur lors de la préparation de la requête : " . $mysqli->error;
            }
        } elseif ($_POST['action'] === "supprimer") {
            // Suppression d'un utilisateur
            $id_utilisateur = $_POST['matricule'];

            $sql = "DELETE FROM utilisateur WHERE ID_utilisateur = ?";

            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("i", $id_utilisateur);

                if ($stmt->execute()) {
                    echo "Utilisateur supprimé avec succès";
                } else {
                    echo "Erreur lors de la suppression de l'utilisateur : " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Erreur lors de la préparation de la requête : " . $mysqli->error;
            }
        }
    }
    $mysqli->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page utilisateur (Admin)</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        /* Styles pour centrer le formulaire */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Styles pour le formulaire */
        .form-icon {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-icon i {
            font-size: 64px;
            color: #0056b3;
        }

        .registration-form {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        .form-control.item {
            margin-bottom: 20px;
            color: #0056b3;
        }

        .create-account {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
        }

        .create-account:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    

    <div class="registration-form">
    <h1><?php
session_start(); // Démarrez la session si ce n'est pas déjà fait
if (isset($_SESSION["utilisateur"]) && isset($_SESSION["utilisateur"]["Role"])) {
    $role = $_SESSION["utilisateur"]["Role"];
    echo "  &nbsp;  &nbsp; &nbsp;WELCOM " ." &nbsp;  "." &nbsp;"." &nbsp;"." &nbsp;"." &nbsp;". $role ; // Ajout du message à côté du rôle
} else {
    echo "  &nbsp;  &nbsp;Bienvenue !"; // Message par défaut si la session utilisateur n'est pas définie ou si le rôle n'est pas défini.
}
?>



        <div class="form-icon">
            <i class="icon icon-user"></i>
        </div>
        
        <form action="utilisateur.php" method="post">
    <div class="form-group">
        <input type="text" class="form-control item" name="matricule" placeholder="Matricule N°" required >
    </div>
    <div class="form-group">
    <input type="text" class="form-control item" name="_username" placeholder="Username"  >
</div>

    <div class="form-group">
        <input type="password" class="form-control item" name="password" placeholder="Password"  >
    </div>
    <div class="form-group">
        <input type="text" class="form-control item" name="role" placeholder="Role" required  >
    </div>
    <div class="form-group">
        <input type="text" class="form-control item" name="Service" placeholder="Service"  >
    </div>

    <!-- Champ caché pour l'action (ajouter, modifier, supprimer) -->
    <input type="hidden" name="action" value="">

<!-- Bouton "Modifier" -->

<!-- Bouton "Supprimer" -->


    <div class="form-group">
        <button type="submit" class="btn btn-block create-account">AJOUTER</button>
        <button type="submit" class="btn btn-block create-account" name="action" value="modifier">Modifier</button>
        <button type="submit" class="btn btn-block create-account" name="action" value="supprimer">Supprimer</button>

    </div>
</form>

    </div>
    
    

</body>
</html>
