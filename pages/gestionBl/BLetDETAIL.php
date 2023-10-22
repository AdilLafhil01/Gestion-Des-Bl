


<?php include_once('../../templetes/header.php'); ?>

<link href="../../css/BL.css" rel="stylesheet" >
<script src="../../js/detail.js"></script>



<style>

dialog {
    position: absolute;
    left: 50vw;
    top: 30vw;
    transform: translate(-50%, -50%);
    margin: 0; /*reset some browser centering*/
    width: 100%; border: none !important;
  border-radius: calc(5px * var(--ratio));
  box-shadow: 0 0 #0000, 0 0 #0000, 0 25px 50px -12px ;
  height:100%;
}
iframe{
    width:100%;
    height:300%;
}
</style>


<?php  

    include_once '../../dao/dao.php';

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }


    
    $blNumber = "";
    $id_client = "";
    $dateBl2 = "";
    $id_utilis = "";
    $dete_signe = "";
    $date_depo = "";
    $formattedDate ="";
    $date_retour = "";
    $ref_artic = "";
    $etat = "";
    $piece = "";
    $id_bl = "";
    $command = "";
    $COMMANTAIRE_actuelle = "";
    $valider = false;
  
    
    if (isset($_POST['rechercher'])) {
        $blNumber = $_POST['rechercheTxt'];
    } elseif (isset($_GET['rechercheTxt'])) {
        $blNumber = $_GET['rechercheTxt'];
    }
    
    if (!empty($blNumber)) {
        $result2 = getOneBl($blNumber); // Supposons que vous ayez une fonction getOneBl pour récupérer les informations du BL
    
        if ($result2->num_rows > 0) {
            $row = $result2->fetch_assoc();
    
            $blNumber = $row['ID_BL'];
            $id_client = $row['ID_Client'];
            $dateBl2 = $row['Date_bl'];
            $formattedDate = date('Y-m-d H:i:s', strtotime($dateBl2));
            $id_utilis = $row['ID_utilisateur'];
            $dete_signe = $row['Date_Signer'];
            $date_depo = $row['Date_Depot'];
            $date_retour = $row['Date_Retour'];
            $command = $row['Num_Commande'];
            $COMMANTAIRE_actuelle = $row['commantaireRecuperer'];
            $etat = $row['Etat_BL'];
            $piece = $row['Piece_jointe'];
            $id_bl = $row['ID_BL'];
            $valider = $row['valider'];
        } else {
            echo '<span style="font-weight: bold; color: red; font-size: 18px;">Aucun résultat trouvé</span>';

        }
    }
    
    // Assurez-vous que $_SESSION["utilisateur"] contient les informations nécessaires

  
    if (isset($_POST["ajouter"])) {
        // Récupérer les données du formulaire
        $id_bl = $_POST['rechercheTxt'];
        $file_name = $_FILES["PIECE_JOINDRE"]["name"];
        $file_tmp = $_FILES["PIECE_JOINDRE"]["tmp_name"];
    
        // Vérifiez si une pièce jointe existe déjà pour cet ID_BL
        $existingAttachment = getAttachmentForBl($id_bl);
    
        // Définissez le chemin du dossier où vous souhaitez enregistrer le fichier dans votre projet
        $upload_directory = "../../upload/pdfFiles/"; // Emplacement où vous souhaitez enregistrer le fichier
    
        if (!$existingAttachment) {
            // Concaténez l'ID_BL avec le nom du fichier pour créer un nom de fichier unique
            $file_name = $id_bl . "_" . $file_name;
    
            // Définissez le chemin complet du fichier sur le serveur
            $target_path = $upload_directory . $file_name;
    
            // Utilisez move_uploaded_file() pour déplacer le fichier vers le dossier cible
            if (move_uploaded_file($file_tmp, $target_path)) {
                // Insérez le nom du fichier dans la base de données
                $result = insertAttachment($id_bl, $file_name);
    
                if ($result) {
                    echo 'Fichier téléchargé et enregistré avec succès avec le nom ' . $file_name;
                } else {
                    echo 'Impossible d\'ajouter la pièce jointe dans la base de données.';
                }
            } else {
                echo 'Impossible de déplacer le fichier vers le dossier.';
            }
        } else {
            echo 'Une pièce jointe existe déjà pour cet ID_BL.';
        }
    }
    
      

    $fileType = "";

    if (isset($_POST['modifier'])) {
        $blNumber = $_POST['rechercheTxt'];
    
        // Assurez-vous de récupérer correctement les autres valeurs
        $ID_CLIENT = $_POST['ID_CLIENT'];
        $dateBl2 = $_POST['DateBl'];
        $nouveauCommentaire = $_POST['NOUVEAU_COMMENTAIRE']; // Utilisez le champ du formulaire approprié
        $COMMANTAIRE_actuelle = $_POST['COMMANTTAIRE']; // Assurez-vous de récupérer le champ actuel du formulaire
    
        // Vous devez définir les autres valeurs comme $id_utilis, $dete_signe, $date_depo, $date_retour, $etat en fonction de votre application
    
        $valider = 1;
    
        // Vérifiez d'abord si un fichier a été téléchargé
        if (isset($_FILES['PIECE_JOINDRE']) && $_FILES['PIECE_JOINDRE']['error'] === UPLOAD_ERR_OK) {
            // Vérifiez la taille du fichier (5 Mo maximum)
            $file_size = $_FILES["PIECE_JOINDRE"]["size"];
            $max_file_size = 5 * 1024 * 1024; // 5 Mo en octets
    
            if ($file_size <= $max_file_size) {
                // Définissez le chemin du dossier où vous souhaitez enregistrer le fichier
                $upload_directory = "../../upload/pdfFiles/";
    
                $file_name = $blNumber . "_" . $_FILES["PIECE_JOINDRE"]["name"];
                $target_path = $upload_directory . $file_name;
    
                if (move_uploaded_file($_FILES["PIECE_JOINDRE"]["tmp_name"], $target_path)) {
                    // Appelez la fonction modifierbl pour mettre à jour les informations
                    $id_utilis = $row['ID_utilisateur'];
                    $date_depo = $_POST['DateBl'];
                    $etat = $_POST['ETAT_BL'];
    
                    // Appelez la fonction modifierbl avec le NOUVEAU commentaire
                    $result = modifierbl($blNumber, $ID_CLIENT, $dateBl2, $id_utilis, $dete_signe, $date_depo, $date_retour, $file_name, $fileType, $etat, $valider, $nouveauCommentaire, $COMMANTAIRE_actuelle, $commantaireRecupere);
    
                    if ($result) {
                        // Enregistrez les informations de traçabilité en appelant la fonction modifierTraçabilité
                        $type_mouvement = 'valider';
                        $date_mouvement = date('Y-m-d H:i:s'); // Vous pouvez personnaliser ce champ
                        $result_traçabilité = modifierTraçabilité($blNumber, $ID_CLIENT, $dateBl2, $id_utilis, $date_depo, $date_retour, $etat, $valider, $type_mouvement, $date_mouvement);
    
                        if ($result_traçabilité) {
                            echo '<span style="font-weight: bold; color: green;">Valider avec succès</span>';
                        } else {
                            echo "Erreur lors de l'enregistrement de la traçabilité : " . $mysqli->error;
                        }
                    } else {
                        echo "Erreur lors de la mise à jour du BL : " . $mysqli->error;
                    }
                } else {
                    echo "Impossible de déplacer le fichier vers le dossier.";
                }
            } else {
                echo "La taille du fichier dépasse la limite de 5 Mo. Veuillez télécharger un fichier plus petit.";
            }
        }
    }
    
       
function getAttachmentForBl($blNumber) {
    global $mysqli;
    
    // Échappez l'ID_BL pour éviter les injections SQL
    $blNumber = mysqli_real_escape_string($mysqli, $blNumber);

    // Créez la requête SQL pour récupérer la pièce jointe
    $query = "SELECT * FROM piece_joindre WHERE ID_BL = '$blNumber'";

    // Exécutez la requête SQL
    $result = $mysqli->query($query);

    // Vérifiez si une pièce jointe existe
    if ($result->num_rows > 0) {
        // Une pièce jointe existe, retournez les données de la pièce jointe
        return $result->fetch_assoc();
    } else {
        // Aucune pièce jointe n'existe
        return null;
    }
}
function insertAttachment($blNumber, $file_name) {
    global $mysqli;

    // Utilisez une requête préparée pour éviter les injections SQL
    $query = "INSERT INTO piece_joindre (ID_BL, Piece_jointe) VALUES (?, ?)";
    
    $stmt = $mysqli->prepare($query);

    // Liez les paramètres et leurs types
    $stmt->bind_param("ss", $blNumber, $file_name);

    // Exécutez la requête préparée
    $result = $stmt->execute();

    // Fermez la requête préparée
    $stmt->close();

    return $result;
}



?>




        
        

                        
                    
<form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
    <div class="row">
        <div class="form-group col-sm-6 flex-column d-flex">
            <span class="position-absolute search">
                <i class="fa fa-search"></i>
            </span>
            <label class="form-control-label px-3">NUM BL<span class="text-danger"> *</span></label>
            <input type="text" class="form-control bl-input" id="numBl" name="rechercheTxt"   placeholder="Search by Num BL" value="<?php echo $blNumber; ?>" >
        </div>
        <div class="form-group col-sm-6 flex-column d-flex">
    <label class="form-control-label px-3">DATE CREATION<span class="text-danger"> *</span></label>
    <input type="text" class="form-control bl-input" id="dateBl" name="DateBl" value="<?= $formattedDate; ?>" placeholder="" readonly>
</div>


        
       
    </div>
    <div class="row">
       
    <div class="form-group col-sm-6 flex-column d-flex">
            <label class="form-control-label px-3">CLIENT<span class="text-danger"> *</span></label>
            <input type="text" class="form-control bl-input" id="ID_CLIENT" name="ID_CLIENT" required value='<?php echo $id_client; ?>' placeholder="" readonly>
        </div>

        <div class="form-group col-sm-6 flex-column d-flex">
                <label class="form-control-label px-3">NUM COMMANDE<span class="text-danger"> </span></label>
                <input type="text" class="form-control bl-input" id="NUM_command" name="NUM_command" value='<?php echo $command; ?>' placeholder="" readonly>
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
        
            <div class="form-group col-sm-10 flex-column d-flex">
    <label class="form-control-label px-3">COMMANTAIRE :<span class="text-danger"> </span></label>
    <textarea class="form-control bl-input" id="COMMENTAIRE" name="COMMANTTAIRE" placeholder="" readonly><?php echo $COMMANTAIRE_actuelle; ?> </textarea>
</div>   
            </div> 
       

    
            <div class="row">
    
   
        
            <div class="form-group col-sm-6 flex-column d-flex">
    <label class="form-control-label px-3">NOUVEAU COMMANTAIRE :<span class="text-danger"> </span></label>
    <textarea class="form-control bl-input" id="NOUVEAU_COMMENTAIRE" name="NOUVEAU_COMMENTAIRE" placeholder="Saisissez le nouveau commentaire"></textarea>
</div>

    <div class="form-group col-sm-4 flex-column d-flex">
        <label class="form-control-label px-3">ETAT_BL<span class="text-danger"> *</span></label>
        <select class="form-control bl-input" id="ETAT_BL" name="ETAT_BL" required onchange="disableSubmitButton(this)">
            <option value="" disabled selected>Choisissez une option</option>
            <option value="signer" <?php if ($etat === 'signer') echo 'selected'; ?>>Signer</option>
            <option value="modifier" <?php if ($etat === 'modifier') echo 'selected'; ?>>Modifier</option>
            <option value="retour" <?php if ($etat === 'retour') echo 'selected'; ?>>Retour</option>
        </select>
    </div>




</div>   
   
        

    

<div class="row">
    <div class="form-group col-sm-4 flex-column d-flex">
        <label class="form-control-label px-3">PIECE_JOINDRE<span class="text-danger"> *</span></label>
        <div class="input-group">
            <input type="file" class="form-control bl-input" id="pc_joindre" name="PIECE_JOINDRE" accept=".pdf" value='<?php echo $name; ?>' placeholder="">
            
        </div>
    </div>
    <div class="form-group col-sm-4 flex-column d-flex">
        <label class="form-control-label px-3">Valider<span class="text-danger"><?php echo $etat; ?> *</span></label>
        <input type="checkbox" class="form-control bl-input" name='valider[]' value="valider" <?php if ($valider == 1) echo 'checked="checked"'; ?>>
    </div>
    <label class="form-control-label px-3" id="etat" name="ETAT_BL" value='<?php echo $etat; ?><'><?php echo $etat; ?><span class="text-danger"> *</span></label>

</div>



    </div>

    <div class="row">
        
        <div class="form-group col-sm-4 flex-column d-flex">
            <button type="submit" id="submitbtn" name="modifier" class="btn btn-block create-account" value='valider'>VALIDER</button>
        </div>
        <div class="form-group col-sm-4 flex-column d-flex">
            <button type="submit" id="recherchbtn" name="rechercher" class="btn btn-block create-account" value='rechercher'>RECHERCHER</button>
        </div>

       
    </div>
</form>



<diV>

</div>





       
 


























        
                 
            
            </div>
        </div>
        
    </div>
   
    



</div>
</div>






</form>
<script>
function disableSubmitButton(select) {
    const submitBtn = document.getElementById("submitbtn");
    const modifierBtn = document.getElementById("modifierbtn");
    const pc_joindre = document.getElementById("pc_joindre");

    if (select.value === "retour") {
        // Si l'option "retour" est sélectionnée, désactivez les boutons
        submitBtn.disabled = true;
        modifierBtn.disabled = true;
        pc_joindre.disabled=true;
    } else {
        // Sinon, activez les boutons
        submitBtn.disabled = false;
        modifierBtn.disabled = false;
        pc_joindre.disabled=false;
    }
}
</script>









<?php include_once('../../templetes/footer.php'); ?>