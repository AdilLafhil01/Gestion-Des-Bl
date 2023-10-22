
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <link href="../../css/detail.css" rel="stylesheet" >
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

// ...

$resultats = array(); // Créez un tableau pour stocker les résultats

if (isset($_POST['ID_UTILISATEUR'])) {
    $id_utilisateur = $_POST['ID_UTILISATEUR']; // Récupérez la valeur saisie dans le champ ID_UTILISATEUR
   // $date_mouvement = $_POST['DATE_MOUVEMENT']; // Récupérez la valeur saisie dans le champ DATE_MOUVEMENT

    // Construisez la requête SQL de base
    $query = "SELECT u.Nom_utilisateur,t.Date_mouvement, t.type_mouvement, t.id_bl FROM traçabilité t, bl b, utilisateur u WHERE t.id_bl = b.id_bl AND t.id_utilisateur = u.ID_utilisateur";

    // Créez un tableau pour stocker les valeurs de liaison des paramètres
    $bind_params = array();

    // Vérifiez si un ID utilisateur est spécifié
    if (!empty($id_utilisateur)) {
        $query .= " AND u.ID_utilisateur = ?";
        $bind_params[] = $id_utilisateur;
    }

    // Vérifiez si une date de mouvement est spécifiée
    //if (!empty($date_mouvement)) {
       // $query .= " AND t.Date_mouvement = ?";
       // $bind_params[] = $date_mouvement;
   // }

    // Utilisation de requêtes préparées pour sécuriser la requête SQL
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        // Créez un tableau des types de données pour les paramètres
        $types = str_repeat('s', count($bind_params));
        // Liez les paramètres
        $stmt->bind_param($types, ...$bind_params);

        if ($stmt->execute()) {
            $resultat = $stmt->get_result();

            if ($resultat->num_rows > 0) {
                while ($row = $resultat->fetch_assoc()) {
                    // Stockez chaque résultat dans le tableau
                    $resultats[] = $row;
                }
            } else {
                // Aucun résultat trouvé, affichez un message d'erreur ou faites ce que vous voulez
                echo '<tr class="no-results-table"><td colspan="20">Aucun résultat trouvé.</td></tr>';
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
  <form action="TracabiliteBL.php" method="post">
      <div class="form-group col-sm-4 flex-column d-flex">
            <span class="position-absolute search"><i class="fa fa-search"></i></span>
            <label class="form-control-label px-3">ID_UTILISATEUR<span class="text-danger"> *</span></label>
            <input type="text" class="form-control bl-input" id="ID_UTILISATEUR" name="ID_UTILISATEUR" placeholder="" onblur=submit()>
        </div>
       

    </div>
    <div class="table-responsive">
    <table class="table table-striped" id="tablerecherch">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nom Utilisateur</th>
                <th scope="col">Date Mouvement</th>
                <th scope="col">NUM BL</th>
                <th scope="col">Type Mouvement</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($resultats as $index => $bl) { ?>
            <tr>
    <th scope="row"><?php echo $index + 1; ?></th>
    <td><?php echo isset($bl['Nom_utilisateur']) ? $bl['Nom_utilisateur'] : ''; ?></td>
    <td><?php echo isset($bl['Date_mouvement']) ? $bl['Date_mouvement'] : ''; ?></td>
    <td><?php echo isset($bl['id_bl']) ? $bl['id_bl'] : ''; ?></td>
    <td><?php echo isset($bl['type_mouvement']) ? $bl['type_mouvement'] : ''; ?></td>
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
</div>





</body>
<script>
document.addEventListener("DOMContentLoaded", function () {
    var input = document.getElementById("ID_UTILISATEUR");
    var messageAucunResultat = document.getElementById("messageAucunResultat");
    var table = document.getElementById("tablerecherch");

    input.addEventListener("input", function () {
        var searchText = input.value.toLowerCase();
        var rows = table.getElementsByTagName("tr");
        var resultFound = false;

        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var numBlCell = row.getElementsByTagName("td")[0];

            if (numBlCell) {
                var numBlText = numBlCell.textContent || numBlCell.innerText;

                if (numBlText.toLowerCase().indexOf(searchText) > -1) {
                    row.style.display = "";
                    resultFound = true; // Indique qu'un résultat a été trouvé
                } else {
                    row.style.display = "none";
                }
            }
        }

        // Affichez ou masquez le message en fonction du résultat
        if (resultFound) {
            messageAucunResultat.style.display = "none"; // Masquez le message
            table.classList.remove("no-results-table"); // Supprimez la classe CSS pour changer la couleur
        } else {
            messageAucunResultat.style.display = "block"; // Affichez le message
            table.classList.add("no-results-table"); // Ajoutez la classe CSS pour changer la couleur
        }
    });
});
</script>

</html>
</form>
<?php include_once('../../templetes/footer.php'); ?>
