


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./css/BL.css" rel="stylesheet">
    <link href="./css/detail.css" rel="stylesheet" >
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    
   
<?php include_once('../../templetes/header.php'); ?>
<link href="../../css/BL.css" rel="stylesheet" >
<script src="../../js/detail.js"></script>
   
    
   
    <?php
// Incluez votre code de connexion à la base de données ici
include_once '../../dao/dao.php';

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
// ...
// ...

$bls = array(); // Créez un tableau pour stocker les résultats

if (isset($_POST['submitdate'])) {
    $id_utilisateur = $_POST['ID_UTILISATEUR']; // Récupérez la valeur saisie dans le champ ID_UTILISATEUR

    // Construisez la requête SQL de base
    $query3 = "SELECT * FROM bl WHERE 1";

    // Créez un tableau pour stocker les valeurs de liaison des paramètres
    $bind_params = array();

    // Vérifiez si un ID utilisateur est spécifié
    if (!empty($id_utilisateur)) {
        $query3 .= " AND ID_utilisateur = ?";
        $bind_params[] = $id_utilisateur;
    }

    // Vérifiez les dates
    if (!empty($_POST['DATE_DEPOT']) && !empty($_POST['DATE_RETOUR'])) {
        $date_sortie = $_POST['DATE_DEPOT'];
        $date_retour = $_POST['DATE_RETOUR'];
        $query3 .= " AND Date_Signer BETWEEN ? AND ?";
        $bind_params[] = $date_sortie;
        $bind_params[] = $date_retour;
    }

    // Utilisation de requêtes préparées pour sécuriser la requête SQL
    $stmt = $mysqli->prepare($query3);

    if ($stmt) {
        // Créez un tableau des types de données pour les paramètres
        $types = str_repeat('s', count($bind_params));
        // Liez les paramètres
        $stmt->bind_param($types, ...$bind_params);

        if ($stmt->execute()) {
            $result3 = $stmt->get_result();

            if ($result3->num_rows > 0) {
                while ($row = $result3->fetch_assoc()) {
                    // Stockez chaque résultat dans le tableau
                    $bls[] = $row;
                }
            } else {
                // Aucun résultat trouvé, affichez un message d'erreur ou faites ce que vous voulez
                echo '<tr class="no-results"><td colspan="5">Aucun résultat trouvé.</td></tr>';
            }
        } else {
            // Gestion de l'erreur d'exécution de la requête
            echo 'Erreur lors de l\'exécution de la requête : ' . $stmt->error;
        }

        // Fermez la déclaration
        $stmt->close();
    } else {
        // Gestion de l'échec de la préparation de la requête
        echo 'Erreur lors de la préparation de la requête : ' . $mysqli->error;
    }
}

// Fermez la connexion à la base de données
$mysqli->close();

?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Vos balises meta, titre, CSS, etc. -->
</head>
<body>

                <form action="RechercheEtStatutBL.php" method="post">
    <div class="form-row justify-content-between text-left">
        <div class="form-group col-sm-4 flex-column d-flex">
            <span class="position-absolute search"><i class="fa fa-search"></i></span>
            <label class="form-control-label px-3">DATE_DEPOT<span class="text-danger"> *</span></label>
            <input type="date" class="form-control bl-input" id="DATE_DEPOT" name="DATE_DEPOT" placeholder="">
        </div>
        <div class="form-group col-sm-4 flex-column d-flex">
            <span class="position-absolute search"><i class="fa fa-search"></i></span>
            <label class="form-control-label px-3">DATE_RETOUR<span class="text-danger"> *</span></label>
            <input type="date" class="form-control bl-input" id="DATE_RETOUR" name="DATE_RETOUR" placeholder="">
        </div>
        <div class="form-group col-sm-4 flex-column d-flex">
            <span class="position-absolute search"><i class="fa fa-search"></i></span>
            <label class="form-control-label px-3">ID_UTILISATEUR<span class="text-danger"> *</span></label>
            <input type="text" class="form-control bl-input" id="ID_UTILISATEUR" name="ID_UTILISATEUR" placeholder="">
        </div>
    </div>
    <div class="form-row justify-content-between text-left">
        <div class="form-group col-sm-4 flex-column d-flex">
            <label class="form-control-label px-3">Critère de recherche<span class="text-danger"> *</span></label>
            <select class="form-control bl-input" id="search_criteria" name="search_criteria">
                <option value="date_range">Intervalle de dates</option>
                <option value="user_id">ID_UTILISATEUR</option>
            </select>
        </div>
    </div>
    <div class="text-center">
        <div class="center-vertical"> <!-- Utilisez la classe pour centrer verticalement -->
            <button type="submit" class="btn btn-block create-account custom-btn mx-auto" name="submitdate" value="rechercher">RECHERCHER</button>
        </div>
    </div>
</form>


                  

<div class="table-responsive">
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">NUM BL</th>
                <th scope="col">Client</th>
                <th scope="col">ID Utilisateur</th>
                <th scope="col">Référence Article</th>
                <th scope="col">Date Signer</th>
                <th scope="col">Date Dépôt</th>
                <th scope="col">Date Retour</th>
                <th scope="col">Date BL</th>
                <th scope="col">État</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bls as $index => $bl) { ?>
                <tr>
                    <th scope="row"><?php echo $index + 1; ?></th>
                    <td><?php echo isset($bl['ID_BL']) ? $bl['ID_BL'] : ''; ?></td>
                    <td><?php echo isset($bl['ID_Client']) ? $bl['ID_Client'] : ''; ?></td>
                    <td><?php echo isset($bl['ID_utilisateur']) ? $bl['ID_utilisateur'] : ''; ?></td>
                    <td><?php echo isset($bl['Ref']) ? $bl['Ref'] : ''; ?></td>
                    <td><?php echo isset($bl['Date_Signer']) ? $bl['Date_Signer'] : ''; ?></td>
                    <td><?php echo isset($bl['Date_Depot']) ? $bl['Date_Depot'] : ''; ?></td>
                    <td><?php echo isset($bl['Date_Retour']) ? $bl['Date_Retour'] : ''; ?></td>
                    <td><?php echo isset($bl['DateBl']) ? $bl['DateBl'] : ''; ?></td>
                    <td><?php echo isset($bl['Etat_BL']) ? $bl['Etat_BL'] : ''; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>


                </div>
            </div>
        </div>
    </div>
</div>





</body>
</html>
<?php include_once('../../templetes/footer.php'); ?>
