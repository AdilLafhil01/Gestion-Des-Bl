
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="../../css/BL.css" rel="stylesheet" >
<script src="../../js/detail.js"></script>








<?php

include_once '../../dao/dao.php';// Incluez votre fichier de configuration de base de données
 // Incluez votre fichier de configuration de base de données
 function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

 $ref = "";
 $quantite = "";
 $description = "";
 $Date_permission = "";
 
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
     if (isset($_POST['enregistrer'])) {
         // Code pour ajouter un nouvel article
         $ref = $_POST['ref'];
         $quantite = $_POST['quantite'];
         $description = $_POST['description'];
         $date_permission = $_POST['date_permission'];
 
         // Assurez-vous que les données récupérées sont sécurisées
         $ref = mysqli_real_escape_string($mysqli, $ref);
         $quantite = mysqli_real_escape_string($mysqli, $quantite);
         $description = mysqli_real_escape_string($mysqli, $description);
         $date_permission = mysqli_real_escape_string($mysqli, $date_permission);
 
         // Requête d'insertion dans la table "article"
         $sql = "INSERT INTO article (Ref, Description, Date_Péremption, Quantité) VALUES (?, ?, ?, ?)";
 
         if ($stmt = $mysqli->prepare($sql)) {
             $stmt->bind_param("ssss", $ref, $description, $date_permission, $quantite);
 
             if ($stmt->execute()) {
                 echo "Article ajouté avec succès.";
             } else {
                 echo "Erreur lors de l'ajout de l'article : " . $stmt->error;
             }
 
             $stmt->close();
         } else {
             echo "Erreur lors de la préparation de la requête : " . $mysqli->error;
         }
     } elseif (isset($_POST['modifier'])) {
         // Code pour modifier un article existant
         $ref = $_POST['ref'];
         $nouvelle_quantite = $_POST['quantite'];
         $nouvelle_description = $_POST['description'];
         $nouvelle_date_permission = $_POST['date_permission'];
 
         // Assurez-vous que les données récupérées sont sécurisées
         $ref = mysqli_real_escape_string($mysqli, $ref);
         $nouvelle_quantite = mysqli_real_escape_string($mysqli, $nouvelle_quantite);
         $nouvelle_description = mysqli_real_escape_string($mysqli, $nouvelle_description);
         $nouvelle_date_permission = mysqli_real_escape_string($mysqli, $nouvelle_date_permission);
 
         // Exécutez la mise à jour dans la table "article"
         $sql = "UPDATE article SET Quantité = ?, Description = ?, Date_Péremption = ? WHERE Ref = ?";
 
         if ($stmt = $mysqli->prepare($sql)) {
             $stmt->bind_param("ssss", $nouvelle_quantite, $nouvelle_description, $nouvelle_date_permission, $ref);
 
             if ($stmt->execute()) {
                 echo "Article modifié avec succès.";
             } else {
                 echo "Erreur lors de la modification de l'article : " . $stmt->error;
             }
 
             $stmt->close();
         } else {
             echo "Erreur lors de la préparation de la requête : " . $mysqli->error;
         }
     }
     elseif (isset($_POST['supprimer'])) {
        // Traitement de la suppression
        $ref = $_POST['ref']; // Obtenez l'ID du BL à supprimer

        // Assurez-vous que l'ID récupéré est sécurisé
        $ref = mysqli_real_escape_string($mysqli, $ref);

        // Exécutez la suppression dans la table "details_bl"
        $sql = "DELETE FROM article WHERE Ref = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $ref);

            if ($stmt->execute()) {
                echo "Article supprimé avec succès.";
            } else {
                echo "Erreur lors de la suppression de l'article : " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Erreur lors de la préparation de la requête : " . $mysqli->error;
        }
    }
 }
 
    
   

    $mysqli->close();

?>








<?php include_once('../../templetes/header.php'); ?>


<form method="POST" accept-charset="utf-8" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="row text-left">
        <div class="form-group col-sm-4 flex-column d-flex">
            <span class="position-absolute search"></span>
            <label class="form-control-label px-3">Référence<span class="text-danger"> *</span></label>
            <input type="text" class="form-control bl-input" required="true" id="ref" name="ref" placeholder="" value="<?php echo $ref; ?>">
        </div>
        
        <div class="form-group col-sm-4 flex-column d-flex">
            <label class="form-control-label px-3">Description<span class="text-danger"> *</span></label>
            <input type="text" class="form-control bl-input" id="description"  name="description" value="<?php echo $description; ?>" placeholder="">
        </div>
        <div class="form-group col-sm-4 flex-column d-flex">
            <label class="form-control-label px-3">Quantité<span class="text-danger"> *</span></label>
            <input type="number" class="form-control bl-input"   name="quantite" value="<?php echo $quantite; ?>" placeholder="">
        </div>
        <div class="form-group col-sm-4 flex-column d-flex">
            <label class="form-control-label px-3">Date de Permission<span class="text-danger"> *</span></label>
            <input type="date" class="form-control bl-input" id="date_permission" name="date_permission"  value="<?php echo $date_permission; ?>" placeholder="">
        </div>
    </div>

    
       
    

    
    <div class="row text-left">
        <div class="form-group col-sm-3 flex-column d-flex">
            <button type="submit" id="btnenregistrer" name="enregistrer" class="btn btn-block create-account" value='enregistrer'>ENREGISTRER</button>
        </div>
        <div class="form-group col-sm-3 flex-column d-flex">
            <button type="submit" id="btnmodifier" name="modifier" class="btn btn-block create-account" value='modifier'>MODIFIER</button>
        </div>
        <div class="form-group col-sm-3 flex-column d-flex">
            <button type="submit" id="btnsupprimer" name="supprimer" class="btn btn-block create-account" value='supprimer'>SUPPRIMER</button>
        </div>
        <div class="form-group col-sm-3 flex-column d-flex">
            <button type="submit" id="btnnouveauarticle" name="nouveaux" class="btn btn-block create-account" value='nouveaux'>NOUVEAUX</button>
        </div>
    </div>
</form>

<script>
    document.getElementById('btnnouveauarticle').addEventListener('click', function () {
        // Réinitialisez les valeurs des champs d'articles à vide
        document.getElementById('ref').value = '';
       
        document.getElementById('quantite').value = '';
        document.getElementById('description').value = '';
        document.getElementById('date_permission').value = '';
        
        // Si vous avez d'autres champs d'articles, ajoutez-les ici

        // Vous pouvez également ajouter d'autres logiques de réinitialisation si nécessaire
    });
</script>
<?php include_once('../../templetes/footer.php'); ?>