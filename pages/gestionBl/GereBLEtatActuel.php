

<?php include_once('../../templetes/header.php'); ?>

<link href="../../css/BL.css" rel="stylesheet" >
<script src="../../js/detail.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    
    $date_retour = "";
    $ref_artic = "";
    $etat = "";
    $piece = "";
    $id_bl = "";
    $command = "";
    $COMMANTAIRE = "";
    $COMMANTAIRE="";
    $valider = false;
    $formattedDate="";
    $type_mouvement="";
    if (isset($_POST['rechercheTxt'])) {
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
            $COMMANTAIRE = $row['commantaireRecuperer'];
            $COMMANTAIRE_actuelle = $row['commantaireActuell'];

           // $etat = $row['Etat_BL'];
            $piece = $row['Piece_jointe'];
            $id_bl = $row['ID_BL'];
            $valider = $row['valider'];
        } else {
            echo "<strong>aucun Resultat Trouvé</strong>";
        }
        
    }
    
    
    
    
    // Assurez-vous que $_SESSION["utilisateur"] contient les informations nécessaires

    function ajouterBl() {
        if (isset($_POST["ajouter"])) {
            // Récupérer les données du formulaire
            $id_bl = $_POST['rechercheTxt'];
            $id_client = $_POST['ID_CLIENT'];
            $dateBl2 = $_POST['DateBl'];
            $command = $_POST['NUM_command'];
            $COMMENTAIRE = $_POST['COMMENTAIRE'];
            $COMMANTTAIRE = $_POST['COMMANTTAIRE'];
            $etat = $_POST['ETAT_BL'];
            $valider = isset($_POST['valider']) ? 1 : 0;
    
            // Récupérer les informations sur le fichier
            $file_name = $_FILES["PIECE_JOINDRE"]["name"];
            $file_tmp = $_FILES["PIECE_JOINDRE"]["tmp_name"];
            $fileType = $_FILES["PIECE_JOINDRE"]["type"];
    
            // Insérer les données dans la base de données en utilisant la fonction insertBL
            $result = insertBL($id_bl, $id_client, $dateBl2, $command, $COMMENTAIRE, $COMMANTTAIRE, $etat, $valider, $file_name);
    
            if ($result) {
                // Définissez le chemin du dossier où vous souhaitez enregistrer le fichier
                $upload_directory = "C:/Piece Joindre BL/";
    
                // Définissez le chemin complet du fichier sur le serveur
                $target_path = $upload_directory . $file_name;
    
                // Utilisez move_uploaded_file() pour déplacer le fichier vers le dossier cible
                // Renommez le fichier par num_Bl s'il existe, il faut le remplacer
                if (move_uploaded_file($file_tmp, $target_path)) {
                    echo 'Fichier téléchargé et enregistré avec succès dans le dossier.';
                } else {
                    echo 'Impossible de déplacer le fichier vers le dossier.';
                }
            } else {
                echo 'Impossible de sauvegarder les informations dans la base de données.';
            }
        }
    }
            ?>
<?php  

 // Include your database connection script

// ... Other code ...

if (isset($_POST['modifier'])) {
    $blNumber = $_POST['rechercheTxt'];
    $ID_CLIENT = $_POST['ID_CLIENT'];
    $dateBl2 = $_POST['DateBl'];
    $COMMANTAIRE_actuelle = $_POST['COMMANTTAIRE'];
    
    // Ensure you obtain other values as needed
    //$dete_signe = $_POST['DATE_SIGNER'];
   // $date_depo = $_POST['DATE_DEPOT'];
    //$date_retour = $_POST['DATE_RETOUR'];
   // $etat = $_POST['ETAT_BL'];
    //$valider = 1; // You have this value, but it's not clear how it's used.

    // Check if the BL with the given ID (blNumber) exists in the 'BL' table
    $existingBl = checkBl($blNumber);

    if ($existingBl) {
        // The BL exists, perform the update
        $result = modifierbl1($blNumber, $ID_CLIENT, $dateBl2, $id_utilis, $dete_signe, $date_depo, $date_retour, $etat);

        if ($result) {
            echo '<strong style="color: green;">Validation de la réception réussie</strong>';
        } else {
            echo "Erreur lors de la mise à jour du BL : " . $mysqli->error;
        }
    } else {
        // The BL does not exist in the 'BL' table. You can take necessary actions.
        echo "ID_BL does not exist in the BL table. Please take appropriate actions.";
    }
}



?>
        
        

                        
                    
<form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
    <div class="row">
        <div class="form-group col-sm-6 flex-column d-flex">
            <span class="position-absolute search">
                <i class="fa fa-search"></i>
            </span>
            <label class="form-control-label px-3">NUM BL<span class="text-danger"> *</span></label>
            <input type="text" class="form-control bl-input" id="numBl" name="rechercheTxt"   placeholder="Search by Num BL" onblur="submit();" value="<?php echo $blNumber; ?>" >
        </div>
        <div class="form-group col-sm-6 flex-column d-flex">
    <label class="form-control-label px-3">DATE CREATION<span class="text-danger"> *</span></label>
    <input type="text" class="form-control bl-input" id="dateBl" name="DateBl" value="<?= $formattedDate; ?>" placeholder="" readonly>
</div>


        
       
    </div>
    <div class="row">
       
    <div class="form-group col-sm-6 flex-column d-flex">
            <label class="form-control-label px-3">CLIENT<span class="text-danger"> </span></label>
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
    <textarea class="form-control bl-input" id="COMMENTAIRE" name="COMMENTAIRE" placeholder="" readonly><?php echo $COMMANTAIRE; ?> </textarea>
</div>   
            </div> 
       

    
            <div class="row">
    
            <div class="form-group col-sm-3 flex-column d-flex">
            <button type="submit" id="submitbtn" name="modifieretat" class="btn btn-block create-account" value='valider'>ETAT ACTUELLE</button>
        </div>
        
        
        <div class="form-group col-sm-6 flex-column d-flex">
                <label class="form-control-label px-3"><span class="text-danger"> </span></label>
                <?php
if (isset($_POST['rechercheTxt'])) {
    $blNumber = $_POST['rechercheTxt'];
    $result = getTraceabilityData($blNumber);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $date_mouvement = $row['Date_mouvement'];
        $type_mouvement = $row['type_mouvement'];

        // Obtenez le nom de l'utilisateur connecté à partir de la session
        if (isset($_SESSION["utilisateur"])) {
            $nom_utilisateur = $_SESSION["utilisateur"]['Nom_utilisateur'];
        } else {
            $nom_utilisateur = "Utilisateur inconnu";
        }

        // Utilisez ces valeurs comme vous le souhaitez, par exemple, les afficher dans une balise <label>
        echo '<label class="form-control-label px-3 font-weight-bold text-danger">État de BL : ' . $type_mouvement . '  Le : ' . $date_mouvement . ' </label>';

        // Enregistrez le mouvement dans la table traçabilité
        $id_bl = $blNumber;
        $id_utilis = $_SESSION["utilisateur"]['ID_utilisateur']; // Remplacez par la manière dont vous obtenez l'ID de l'utilisateur
        $type_mouvement = $row['type_mouvement']; // Remplacez par le type de mouvement approprié
        $date_mouvement = date('Y-m-d H:i:s'); // Utilisez la date actuelle

        $result2 = modifierTraçabilité($id_bl, $id_utilis, $type_mouvement, $date_mouvement);

        
    } else {
        echo "Aucune donnée de traçabilité trouvée pour ce numéro BL.";
    }
}

?>


            </div>
            <div class="form-group col-sm-2 flex-column d-flex">

    </div>

    <div class="form-group col-sm-10 flex-column d-flex">
<label class="form-control-label px-3">COMMANTAIRE :<span class="text-danger"> </span></label>
<textarea class="form-control bl-input" id="COMMENTAIRE" name="" readonly placeholder=""><?php echo  $type_mouvement; ?></textarea>

</div> 

</div>   
   
        
<div class="row">
        
<input type="text" class="form-control bl-input" id="" name=""
    value="Si le BL est déposé chez le backoffice, merci de Valider la réception"
    style="font-weight: bold; color: black;" placeholder="">


        

       
        </div>
    

<div class="row">
    <div class="form-group col-sm-8 flex-column d-flex">
    <label class="form-control-label px-3">COMMANTAIRE :<span class="text-danger"> </span></label>
    <textarea class="form-control bl-input" id="COMMENTAIRE" name="COMMANTTAIRE"  placeholder=""></textarea>

    </div>
    <div class="form-group col-sm-4 flex-column d-flex">
            <button type="submit" id="submitbtn" name="modifier" class="btn btn-block create-account" value='valider'>Valider La Reception</button>
        </div>
    
        
</div>


</form>



<diV>

</div>





       
 

































<div id="pdfContainer"></div>

<script>
    
    var afficherPdfButton = document.getElementById('afficherPdfButton');
    var pdfContainer = document.getElementById('pdfContainer');

    
    afficherPdfButton.addEventListener('click', function() {
        
        var iframe = document.createElement('iframe');
        
        
        iframe.src = '<?php echo $pdf_path; ?>';
        iframe.width = '100%';
        iframe.height = '500px';
        iframe.frameBorder = '0';

        
        pdfContainer.innerHTML = '';
        pdfContainer.appendChild(iframe);
    });
</script>


        
                 
            
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