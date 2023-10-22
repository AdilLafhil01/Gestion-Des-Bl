

<?php include_once('../../templetes/header.php'); ?>

<link href="../../css/BL.css" rel="stylesheet" >
<script src="../../js/detail.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    
    $date_retour = "";
    $ref_artic = "";
    $etat = "";
    $piece = "";
    $id_bl = "";
    $command = "";
    $COMMANTAIRE = "";
    $type_mouvement="";
    
    $valider = false;
    $COMMANTAIRE_actuelle="";
    $COMMANTAIRE_Signee="" ;
    
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
            $id_utilis = $row['ID_utilisateur'];
            $dete_signe = $row['Date_Signer'];
            $date_depo = $row['Date_Depot'];
            $date_retour = $row['Date_Retour'];
            $command = $row['Num_Commande'];
            $COMMANTAIRE = $row['commantaireRecuperer'];
            $COMMANTAIRE_actuelle = $row['commantaireActuell'];
            $COMMANTAIRE_Signee = $row['commentaireSigner'];

            $etat = $row['Etat_BL'];
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
            $result = insertBL($id_bl, $id_client, $dateBl2, $id_utilis, $date_retour, $file_name, $etat, $dete_signe, $date_depo, $fileType, $command, $COMMANTTAIRE);
    
            if ($result) {
                // Définissez le chemin du dossier où vous souhaitez enregistrer le fichier
                $upload_directory = "../../upload/pdfFiles/";
    
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

if (isset($_POST['modifier'])) {
    $blNumber = $_POST['rechercheTxt'];
    $ID_CLIENT = $_POST['ID_CLIENT'];
    $dateBl2 = $_POST['DateBl'];
    $COMMANTAIRE_actuelle= $_POST['COMMANTTAIRE'];
    $COMMANTAIRE= $_POST['COMMANTTAIRES'];
    //$id_utilis = $_POST['ID_UTILISATEUR'];
    //$dete_signe = $_POST['DATE_SIGNER'];
    //$date_depo = $_POST['DATE_DEPOT'];
    //$date_retour = $_POST['DATE_RETOUR'];
    //$etat = $_POST['ETAT_BL'];
    //$valider = 1;

    // Vérifiez si l'ID_BL existe déjà
    $existingBl = getOneBl($blNumber);

    if ($existingBl->num_rows > 0) {
        // L'ID_BL existe déjà, effectuez une mise à jour (UPDATE)
        $result = modifierbl($blNumber, $ID_CLIENT, $dateBl2, $id_utilis, $dete_signe, $date_depo, $date_retour, $file_name, $fileType, $etat, $valider, $COMMANTAIRE_actuelle, $COMMANTAIRE, $COMMANTAIRE);
    } else {
        // L'ID_BL n'existe pas, effectuez une insertion (INSERT)
        $result = insertBl($id_bl, $id_client, $dateBl2, $id_utilis, $date_retour, $file_name, $etat, $dete_signe, $date_depo, $fileType, $command, $COMMANTTAIRE);
    }

    if ($result) {
        // Enregistrez les informations de traçabilité en appelant la fonction
        $type_mouvement = 'valider';
        $result_traçabilité = enregistrerTraçabilité($blNumber, $id_utilis, $type_mouvement, date('Y-m-d H:i:s'));

        if ($result_traçabilité) {
            echo "<strong>Modifier avec succès et traçabilité enregistrée avec succès</strong>";

        } else {
            echo "Erreur lors de l'enregistrement de la traçabilité : " . $mysqli->error;
        }
    } else {
        echo "Erreur lors de la mise à jour ou de l'insertion du BL : " . $mysqli->error;
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
            <label class="form-control-label px-3">DATE CREATION<span class="text-danger"> </span></label>
            <input type="date" class="form-control bl-input" id="dateBl" name="DateBl" value='<?php echo $dateBl2; ?>' placeholder="" readonly>
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
    <textarea class="form-control bl-input" id="COMMENTAIRE" name="COMMENTAIRE" placeholder="Saisissez  le Commantaire" readonly><?php echo $COMMANTAIRE; ?> </textarea>
</div>   
            </div> 
       

    
            <div class="row">
    <!-- Première colonne -->
    <div class="form-group col-sm-3 flex-column d-flex">
        <button type="submit" id="submitbtn" name="modifieretatsignee" class="btn btn-block create-account" value='signee'>SIGNEE</button>
    </div>

    <!-- Deuxième colonne -->
    <div class="form-group col-sm-6 flex-column d-flex">
        <label class="form-control-label px-3"><span class="text-danger"></span></label>
        <?php
 
 if (isset($_POST['rechercheTxt'])) {
     $blNumber = $_POST['rechercheTxt'];
     $result = getTraceabilityData($blNumber);
 
     if ($result->num_rows > 0) {
         $row = $result->fetch_assoc();
         $date_mouvement = $row['Date_mouvement'];
         $type_mouvement = 'Signee';
 
         // Utilisez ces valeurs comme vous le souhaitez, par exemple, les afficher dans une balise <label>
         echo '<label class="form-control-label px-3 font-weight-bold text-danger">État de BL : ' . $type_mouvement . '  Le : ' . $date_mouvement . ' Par : ' . $row['Nom_utilisateur'] . ' </label>';
 
         // Enregistrez le mouvement dans la table traçabilité
         $id_bl = $blNumber;
         $id_utilis = $_SESSION["utilisateur"]['ID_utilisateur']; // Remplacez par la manière dont vous obtenez l'ID de l'utilisateur
         $type_mouvement = $row['type_mouvement']; // Utilisez le type de mouvement obtenu
         $date_mouvement = date('Y-m-d H:i:s'); // Utilisez la date actuelle
 
         // Assurez-vous que la fonction modifierTraçabilité est correctement définie et fonctionne comme prévu
         $result2 = modifierTraçabilité($id_bl, $id_utilis, $type_mouvement, $date_mouvement);
 
        
     } else {
         echo "Aucune donnée de traçabilité trouvée pour ce numéro BL.";
     }
 }
 ?>
 



      
    </div>

    <!-- Troisième colonne -->
    <div class="form-group col-sm-4 flex-column d-flex">
        <label class="form-control-label px-3">PIECE_JOINDRE<span class="text-danger"> *</span></label>
        <div class="input-group">
            <input type="file"  class="form-control form-control-lg" id="PIECE_JOINDRE" name="PIECE_JOINDRE" accept=".pdf" value='<?php echo $name; ?>' placeholder="">
            <div class="input-group-prepend">
                <button type="button" class="bi bi-eye" onClick='dialog.show()'>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"></path>
  <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"></path>
</svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Quatrième colonne -->
    <div class="form-group col-sm-2 flex-column d-flex">
        <label class="form-control-label px-3" id="etat" name="ETAT_BL" value=''></label>
    </div>

    <!-- Cinquième colonne -->
    <div class="form-group col-sm-10 flex-column d-flex">
        <label class="form-control-label px-3">COMMANTAIRE :<span class="text-danger"> </span></label>
        <textarea class="form-control bl-input" id="COMMENTAIRE" name="COMMANTTAIRES" readonly placeholder=""><?php echo $type_mouvement; ?></textarea>
    </div>
</div>
<div class="row">
    
    <div class="form-group col-sm-3 flex-column d-flex">
    <button type="submit" id="submitbtn" name="modifieretat" class="btn btn-block create-account" value='valider'>VALIDER</button>
</div>


<div class="form-group col-sm-6 flex-column d-flex">
        <label class="form-control-label px-3"><span class="text-danger"> </span></label>
        <?php
if (isset($_POST['rechercheTxt'])) {
    $blNumber = $_POST['rechercheTxt'];
    $result = getOneValider($blNumber);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $type_mouvement = 'valider';
            $date_mouvement = $row['Date_mouvement'];
            $nomUtilisateur = $row['Nom_utilisateur'];

            // Utilisez ces valeurs pour afficher dans une balise <label>.
            if ($nomUtilisateur == 'youssri') {
                echo '<label class="form-control-label px-3"><strong style="font-size: 20px;"> '.$type_mouvement.'    Le : ' . $date_mouvement . ' par ' . $nomUtilisateur . '</strong></label>';
            } else {
                echo 'L\'utilisateur n\'est pas "youssri".';
            }        } else {
            echo "Aucune donnée trouvée pour cet ID_BL.";
        }
    } else {
        echo "Une erreur s'est produite lors de la requête.";
    }

    // Après avoir affiché les résultats, enregistrez le mouvement dans la table traçabilité
    $id_bl = $blNumber;
    $id_utilis = $_SESSION["utilisateur"]['ID_utilisateur']; // Remplacez par la manière dont vous obtenez l'ID de l'utilisateur
    $type_mouvement = 'valider'; // Remplacez par le type de mouvement approprié
    $date_mouvement = date('Y-m-d H:i:s'); // Utilisez la date actuelle

    $result2 = modifierTraçabilité($id_bl, $id_utilis, $type_mouvement, $date_mouvement);

   
}

?>


    </div>
    <div class="form-group col-sm-2 flex-column d-flex">
    <label class="form-control-label px-3" id="etat" name="ETAT_BL" value=''> </label>

</div>

<div class="form-group col-sm-10 flex-column d-flex">
<label class="form-control-label px-3">COMMANTAIRE :<span class="text-danger"> </span></label>
<textarea class="form-control bl-input" id="COMMENTAIRE" name="COMMANTTAIRE" readonly placeholder=""></textarea>

</div> 

</div>   

   
        

</form>

<dialog  id="dialog" >
				  <diV>
					  <form method="dialog">
					  
						  <div class="grid">
							  <div class="row position-relative">
							  <button class="close ml-4" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							  </button>
							  </div>
							  <div class="row">
							  <iframe src="../../upload/pdfFiles/bl50_Report2.pdf"   style="position: absolute;height: 85%; width:95%; border: none" ></iframe>
							  </div>
						  </div>
					  </form>
				  </div>
			  </dialog>


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