


<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="../../css/BL.css" rel="stylesheet" >
<script src="../../js/detail.js"></script>

<?php include_once('../../templetes/header.php'); ?>
<?php
include_once '../../dao/dao.php';

  global $Lstarticle;
  global $article;
 global $bls ;
 $addDet=false;


 function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }





$date_bl="";
$id_client="";
$num_bl = "";
    $ref_article = "";

    // Code pour mettre à jour les détails de l'article dans la base de données
    $Quantité = "";
    $description = "";
    $Date_Péremption = "";
    $command = "";
    $COMMANTAIRE = "";

    $resultart = getAllArticles();

    $Lstarticle=array();
    while($row=$resultart->fetch_assoc()){
    $Lstarticle[]=$row;
    }
    
    if(isset(($_POST['addDet']))){
        $addDet=true;
    }

    $sel="";
    if(isset($_POST['lstArt'])){
       
        $sel= $_POST['lstArt'];
        $article=getArticleByRef($sel)->fetch_assoc();
        $addDet=true;
  }
  $etatrecuperer="";
  
  if (isset($_POST['enregistrer'])) {
    $row = $_SESSION["utilisateur"];

    // Récupérez les données du formulaire pour le BL
    $num_bl = $_POST['ID_BL'];
    $ref_article = $_POST['ref_article'];
    $Quantité = $_POST['Quantité'];
    $command = isset($_POST['NUM_command']) ? $_POST['NUM_command'] : '';
    $id_client = isset($_POST['ID_CLIENT']) ? $_POST['ID_CLIENT'] : '';
    $COMMANTAIRE = isset($_POST['COMMENTAIRE']) ? $_POST['COMMENTAIRE'] : '';

    // Assurez-vous que les valeurs requises ne sont pas vides

    $ID_utilisateur = $row['ID_utilisateur']; // Récupérez l'ID de l'utilisateur depuis la session

    // Étape 1 : Insérer un nouveau BL dans la table 'bl'
    $query = "INSERT INTO bl (ID_BL, Num_Commande, ID_Client, Date_bl, commantaireRecuperer) VALUES (?, ?, ?, NOW(), ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssss", $num_bl, $command, $id_client, $COMMANTAIRE);

    if ($stmt->execute()) {
           // Étape 2 : Utilisez le nouveau ID_BL pour insérer des détails de l'article
        // ...

        // Étape 3 : Appel de la fonction pour enregistrer la traçabilité
        $Type_mouvement = 'Recuperer';
        $Date_mouvement = date('Y-m-d H:i:s');

        $result2 = enregistrerTraçabilité($num_bl, $Type_mouvement, $Date_mouvement, $ID_utilisateur);

        if ($result2) {
            echo '<p style="font-weight: bold; font-size: 18px; color: green;">Nouveau Bon de Livraison a été ajouté avec succès avec ID_BL : ' . $num_bl . '</p>';
        } else {
            echo "Erreur lors de l'enregistrement de la traçabilité : " . $mysqli->error;
        }

        $stmt->close(); // Fermez la première déclaration ici
    } else {
        echo "Erreur lors de l'ajout du Bon de Livraison : " . $stmt->error;
    }
}

$ref = $quantite = $description = $date_permission = $ID_BL = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['ajoutArt'])) {
            // Récupération des données du formulaire
            $ref = mysqli_real_escape_string($mysqli, $_POST['ref_article']);
            $quantite = mysqli_real_escape_string($mysqli, $_POST['Quantité']);
            $description = mysqli_real_escape_string($mysqli, $_POST['description']);
            $date_permission = mysqli_real_escape_string($mysqli, $_POST['Péremption']);
            $ID_BL = mysqli_real_escape_string($mysqli, $_POST['ID_BL']); // Assurez-vous que vous avez bien récupéré ID_BL
    
            // Requête d'insertion dans la table "article"
            $sql_article = "INSERT INTO article (Ref, Description, Date_Péremption, Quantité) VALUES (?, ?, ?, ?)";
    
            if ($stmt_article = $mysqli->prepare($sql_article)) {
                $stmt_article->bind_param("ssss", $ref, $description, $date_permission, $quantite);
    
                if ($stmt_article->execute()) {
                    // L'article a été ajouté avec succès à la table "article".
                    // Ajoutons également à la table "details_bl".
                    // Assurez-vous que $ID_BL est un ID_BL existant dans la table "details_bl".
                    $sql_details_bl = "INSERT INTO details_bl (ID_BL, Ref, Quantité_livrer) VALUES (?, ?, ?)";
    
                    if ($stmt_details_bl = $mysqli->prepare($sql_details_bl)) {
                        $stmt_details_bl->bind_param("sss", $ID_BL, $ref, $quantite);
    
                        if ($stmt_details_bl->execute()) {
                            echo "<p style='font-weight: bold; color: green;'>Article ajouté avec succès dans la table 'article' et 'details_bl'.</p>";

                        } else {
                            echo "Erreur lors de l'ajout de l'article dans la table 'details_bl' : " . $stmt_details_bl->error;
                        }
    
                        $stmt_details_bl->close();
                    } else {
                        echo "Erreur lors de la préparation de la requête pour la table 'details_bl' : " . $mysqli->error;
                    }
    
                    $stmt_article->close();
                } else {
                    echo "Erreur lors de l'ajout de l'article dans la table 'article' : " . $stmt_article->error;
                }
            } else {
                echo "Erreur lors de la préparation de la requête pour la table 'article' : " . $mysqli->error;
            }
        }
    
        // Réinitialisation des champs du formulaire
        $ref = $quantite = $description = $date_permission = $ID_BL = "";
    }
    
    

if(isset($_POST['annulerDetail'])){
    $addDet=false;
}
if(isset($_POST['ajouDetail'])){
    ajouterDetail($sel,$num_bl,$_POST['Quantité']);
    $addDet=false;
    print_r("ajoutter avec succeé");
}





if (isset($_POST['modifier'])) {
    $row = $_SESSION["utilisateur"];
    
    // Récupérez les données du formulaire de modification
    $num_bl = $_POST['ID_BL'];
    $ref_article = $_POST['ref_article'];
    $Quantité = $_POST['Quantité'];
    $description = $_POST['description'];
    $Date_Péremption = $_POST['Péremption'];
    $command = $_POST['NUM_command'];
    $COMMANTAIRE = $_POST['COMMENTAIRE'];
    $ID_utilisateur = $row['ID_utilisateur'];

    // Étape 1 : Mettez à jour la table 'bl'
    $sql_bl = "UPDATE bl SET Num_Commande = ? WHERE ID_BL = ?";
    $stmt_bl = $mysqli->prepare($sql_bl);
    
    $stmt_bl->bind_param("si", $command, $num_bl);
    
    if ($stmt_bl->execute()) {
        // Étape 2 : Mettez à jour la table 'details_bl' en utilisant l'ID_BL
        $sql_details_bl = "UPDATE details_bl SET Quantité_livrer = ? WHERE Ref = ? AND ID_BL = ?";
        $stmt_details_bl = $mysqli->prepare($sql_details_bl);
        $stmt_details_bl->bind_param("sss", $Quantité, $ref_article, $num_bl);
    
        if ($stmt_details_bl->execute()) {
            // Étape 3 : Appel de la fonction pour modifier la traçabilité
            $Type_mouvement = 'bl modifier'; // Le bon type de mouvement
            $Date_mouvement = date('Y-m-d H:i:s'); // Utilisez la date actuelle
            // Remplacez par votre ID d'utilisateur
    
            $result = modifierTraçabilité($num_bl, $Type_mouvement, $Date_mouvement, $ID_utilisateur);
    
            if ($result) {
                echo '<p style="font-weight: bold; font-size: 18px; color: red;">Les détails de l\'article avec la référence ' . $ref_article . ' ont été modifiés avec succès.</p>';
            } else {
                echo "Erreur lors de la modification de la traçabilité.";
            }
        } else {
            echo "Erreur lors de la modification des détails de l'article.";
        }
        $stmt_details_bl->close();
    } else {
        echo "Erreur lors de la modification de la table 'bl'.";
    }
    $stmt_bl->close();
}


if (isset($_POST['supprimer'])) {
    // Récupérez les données du formulaire pour la suppression
    $num_bl = $_POST['ID_BL'];
    $ref_article = $_POST['ref_article'];

    // Utilisez une requête SQL DELETE pour supprimer les détails de l'article
    $sql = "DELETE FROM details_bl WHERE Ref = ? AND ID_BL = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("si", $ref_article, $num_bl);

    if ($stmt->execute()) {
        echo '<p style="font-weight: bold; font-size: 18px; color: red;">L\'article avec la référence ' . $ref_article . ' a été supprimé avec succès du Bon de Livraison ' . $num_bl . '.</p>';
    } else {
        echo "Erreur lors de la suppression de l'article.";
    }
}




$mysqli->close();

 ?>

<!DOCTYPE html>
<html>
<head>
    <title>creation Bl</title>
</head>
<body>
<form method="post" accept-charset="utf-8" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="container">
        <div class="row text-left">
            <div class="form-group col-sm-4 flex-column d-flex">
                <label class="form-control-label px-3">NUM BL<span class="text-danger"> *</span></label>
                <input type="text" class="form-control bl-input" id="ID_BL" name="ID_BL" value="<?php echo isset($_POST['ID_BL']) ? $_POST['ID_BL'] : ''; ?>">
            </div>
            <div class="form-group col-sm-4 flex-column d-flex">
                <label class="form-control-label px-3">CLIENT<span class="text-danger"> </span></label>
                <input type="text" class="form-control bl-input" id="ID_CLIENT" name="ID_CLIENT" value="<?php echo isset($_POST['ID_CLIENT']) ? $_POST['ID_CLIENT'] : ''; ?>">
            </div>
            <div class="form-group col-sm-4 flex-column d-flex">
                <label class="form-control-label px-3">DATE CREATION<span class="text-danger"> *</span></label>
                <input type="date" class="form-control bl-input" id="DATE_DEPOT" name="DATE_DEPOT" value="<?php echo isset($_POST['DATE_DEPOT']) ? $_POST['DATE_DEPOT'] : ''; ?>">
            </div>
        </div>
        <div class="row justify-content-between text-left">
            <div class="form-group col-sm-4 flex-column d-flex">
                <label class="form-control-label px-3">REFERENCE ARTICLE<span class="text-danger"> *</span></label>
                <input type="text" class="form-control bl-input" id="ref_article" name="ref_article" value="<?php echo isset($_POST['ref_article']) ? $_POST['ref_article'] : ''; ?>">
            </div>
            <div class="form-group col-sm-4 flex-column d-flex">
                <label class="form-control-label px-3">NUM COMMANDE<span class="text-danger"> </span></label>
                <input type="text" class="form-control bl-input" id="NUM_command" name="NUM_command" value="<?php echo isset($_POST['NUM_command']) ? $_POST['NUM_command'] : ''; ?>">
            </div>
            <div class="form-group col-sm-4 flex-column d-flex">
                <label class="form-control-label px-3">Date Péremption<span class="text-danger"> </span></label>
                <input type="date" class="form-control bl-input" id="Péremption" name="Péremption" value="<?php echo isset($_POST['Péremption']) ? $_POST['Péremption'] : ''; ?>">
            </div>
        </div>
        <div class="row justify-content-between text-left">
            <div class="form-group col-sm-6 flex-column d-flex">
                <label class="form-control-label px-3">Quantité livrer<span class="text-danger"> *</span></label>
                <input type="text" class="form-control bl-input" id="Quantité" name="Quantité" value="<?php echo isset($_POST['Quantité']) ? $_POST['Quantité'] : ''; ?>" placeholder="">
            </div>
            <div class="form-group col-sm-6 flex-column d-flex">
                <label class="form-control-label px-3">DESCRIPTION<span class="text-danger"> </span></label>
                <textarea class="form-control bl-input" id="description" name="description"><?php echo isset($_POST['description']) ? $_POST['description'] : ''; ?></textarea>
            </div>
        </div>
    </div>
           
            </div>  
            <div class="row justify-content-between text-left">
            <div class="form-group col-sm-6 flex-column d-flex">
    <label class="form-control-label px-3">RECUPERER PAR :    <span class="text-danger"><?php  
         // Assurez-vous que la session a été démarrée
        if (isset($_SESSION["utilisateur"])) {
            $row = $_SESSION["utilisateur"];
            echo $row['Nom_utilisateur'];
        }
        ?> </span></label>
    
</div>
            
            <div class="form-group col-sm-6 flex-column d-flex">
    <label class="form-control-label px-3">LE :<span class="text-danger">      <?php
        // Utilisez la fonction date() pour obtenir la date et l'heure actuelles
        echo date("d/m/Y H:i:s"); // Par exemple, "01/12/2023 14:30:45" pour le 1er décembre 2023 à 14:30:45
        ?> </span>
       
    </label>
</div>


</div>
<div class="row justify-content-between text-left">
        
            <div class="form-group col-sm-8 flex-column d-flex">
    <label class="form-control-label px-3">COMMANTAIRE :<span class="text-danger"> </span></label>
    <textarea class="form-control bl-input" id="COMMENTAIRE" name="COMMENTAIRE" placeholder="Saisissez  le Commantaire"><?php echo $COMMANTAIRE; ?></textarea>
</div>   

            </div>   
            
        
        <!-- Bouton pour soumettre le formulaire -->
        <div class="row  text-left">
        <div class="form-group col-sm-6 flex-column d-flex"> 
                  
                  <button type="submit" id ="btnarticle" name="ajoutArt" class="btn btn-block create-account" value='ajouterArt' >AJOUTER ARTICLE </button>
              </div>
				  <div class="form-group col-sm-6 flex-column d-flex"> 
                  
					  <button type="submit" id ="btnenregistrer" name="enregistrer" class="btn btn-block create-account" value='entregistrer' >AJOUTER BL </button>
				  </div>
				  
                  <div class="form-group col-sm-4 flex-column d-flex">    
                  <button type="submit" id="btsupprimer"  name="supprimer" class="btn btn-block create-account" value='supprimer' >SUPPRIMER</button>
				  </div>
                  <div class="form-group col-sm-4 flex-column d-flex">  
                  <button type="submit" id="btnmodifier"  name="modifier" class="btn btn-block create-account" value='modifier' >MODIFIER</button>
				  </div>
                  <div class="form-group col-sm-4 flex-column d-flex">
                <!-- Ajoutez le bouton "Nouveaux" pour réinitialiser les champs -->
                <button type="submit" id="nouveau" name="nouveaux" class="btn btn-primary">Nouveaux</button>
            </div>
                  
                  
                  
                  
			</div>
      </form>



      <script>
    document.getElementById('nouveau').addEventListener('click', function () {
        // Réinitialisez les valeurs des champs d'articles à vide
        document.getElementById('ID_BL').value = '';
        document.getElementById('ID_CLIENT').value = '';
        document.getElementById('DATE_DEPOT').value = '';
        document.getElementById('ref_article').value = '';
        document.getElementById('NUM_command').value = '';
        document.getElementById('Péremption').value = '';
        document.getElementById('Quantité').value = '';
        document.getElementById('description').value = '';
        document.getElementById('COMMENTAIRE').value = '';

        // Si vous avez d'autres champs, ajoutez-les ici

        // Vous pouvez également ajouter d'autres logiques de réinitialisation si nécessaire
    });
</script>
</body>
</html>







        
      
       

			

				  
              <?php include_once('../../templetes/footer.php'); ?>
			  