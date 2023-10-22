<?php include_once('config.php');
function getOneBl($num_bl) {
    global $mysqli;
    $query = "SELECT * FROM bl WHERE ID_BL = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $num_bl);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}
function getTraceabilityData($num_bl) {
    global $mysqli;

    // Sélectionnez les données de traçabilité pour le numéro BL spécifié
    $query = "SELECT u.Nom_utilisateur, t.Date_mouvement, t.type_mouvement FROM traçabilité t
              INNER JOIN bl b ON t.id_bl = b.id_bl
              INNER JOIN utilisateur u ON t.id_utilisateur = u.ID_utilisateur
              WHERE t.id_bl = ?";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $num_bl);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    return $result;
}
function getOneBlSignee($num_bl) {
    global $mysqli;
    $query = "SELECT bl.Date_Signer, utilisateur.Nom_utilisateur
    FROM bl
    INNER JOIN traçabilité ON bl.ID_BL = traçabilité.ID_BL
    INNER JOIN utilisateur ON traçabilité.id_utilisateur = utilisateur.ID_utilisateur
    WHERE utilisateur.ID_utilisateur = 2 AND bl.Etat_BL = 'signer' AND bl.ID_BL = ?
    LIMIT 1";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $num_bl);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}


function getOneValider($num_bl) {
    global $mysqli;
    $query = "SELECT traçabilité.Date_mouvement, utilisateur.Nom_utilisateur
    FROM bl
    INNER JOIN traçabilité ON bl.ID_BL = traçabilité.ID_BL
    INNER JOIN utilisateur ON traçabilité.id_utilisateur = utilisateur.ID_utilisateur
    WHERE traçabilité.type_mouvement = 'valider' AND bl.ID_BL = ?
    LIMIT 1"; // Ajout de LIMIT 1 pour n'obtenir qu'un seul enregistrement
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $num_bl);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function getBlRecuperer() {
    global $mysqli;
    $query = "SELECT COUNT(*) FROM bl WHERE bl.Etat_BL = 'Recuperer'";
    
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}






function getAllBl(){
    $query2 = "SELECT * FROM bl ";
    global $mysqli;
    $result2 = $mysqli->query($query2);
    
    return $result2;
}
function getDetailBl($num_bl){
    $query2 = "SELECT * FROM details_bl WHERE ID_BL= '$num_bl'";
    global $mysqli;
    $result2 = $mysqli->query($query2);
    return $result2;
}
function countBl($statut){
    global $mysqli;
    
    
    $query = "SELECT COUNT(*) FROM bl WHERE etat_BL = '$statut'";
    
    $result = $mysqli->query($query);
    
    
    if ($result) {
        
        $count = $result->fetch_row();
        return $count[0]; 
    } else {
        
        return false;
    }
}
function countBlSigner($status) {
    global $mysqli;
    
    $query = "SELECT COUNT(*) FROM bl WHERE etat_BL = '$status'";
    
    $result = $mysqli->query($query);
    
    
    if ($result) {
        
        $count = $result->fetch_row();
        return $count[0]; 
    } else {
       
        return false;
    }
}
function countBlSignerres() {
    global $mysqli;
    
    $query = "SELECT COUNT(*) FROM bl WHERE etat_BL = 'Signer'";
    
    $result = $mysqli->query($query);
    
    
    if ($result) {
        
        $count = $result->fetch_row();
        return $count[0]; 
    } else {
       
        return false;
    }
}
function countEncour() {
    global $mysqli;
    
    $query = "SELECT COUNT(*) FROM bl WHERE etat_BL = 'En cours'";
    
    $result = $mysqli->query($query);
    
    
    if ($result) {
        
        $count = $result->fetch_row();
        return $count[0]; 
    } else {
       
        return false;
    }
}
function countBlRetour($statuR) {
    global $mysqli;
    
    $query = "SELECT COUNT(*) FROM bl WHERE etat_BL = '$statuR'";
    
    $result = $mysqli->query($query);
    
    
    if ($result) {
        
        $count = $result->fetch_row();
        return $count[0]; 
    } else {
        
        return false;
    }
}
function countIDbl() {
    global $mysqli;
    
    $query = "SELECT COUNT(*) FROM bl ";
    
    $result = $mysqli->query($query);
    
    
    if ($result) {
        
        $count = $result->fetch_row();
        return $count[0]; 
    } else {
       
        return false;
    }
}


function FiltrerBL($date_sortie, $date_retour, $id_utilisateur){
    global $mysqli;
    
    
    $query3 = "SELECT * FROM bl WHERE Date_Signer BETWEEN ? AND ? AND ID_utilisateur = ?";
    $stmt = $mysqli->prepare($query3);
    if ($stmt) {
        $stmt->bind_param('sss', $date_sortie, $date_retour, $id_utilisateur);

        if ($stmt->execute()) {
            $result3 = $stmt->get_result();

            if ($result3->num_rows > 0) {
                while ($row = $result3->fetch_assoc()) {
                    
                    $bls[] = $row;
                }
            } else {
                
                echo '<tr class="no-results"><td colspan="5">Aucun résultat trouvé.</td></tr>';
                
            }
        } else {
         
            echo 'Erreur lors de l\'exécution de la requête : ' . $stmt->error;
        }  
        $stmt->close();
    } else {
       
        echo 'Erreur lors de la préparation de la requête : ' . $mysqli->error;
    }
   
     return $result3;
 
}





function modifierbl($blNumber, $ID_CLIENT, $dateBl2, $id_utilis, $dete_signe, $date_depo, $date_retour, $file_name, $fileType, $etat, $valider, $nouveauCommentaire, $COMMANTAIRE_actuelle, $commantaireRecupere) {
    global $mysqli;

    $query = "UPDATE bl SET 
        ID_Client = ?, 
        Date_bl = ?, 
        ID_utilisateur = ?, 
        Date_Retour = ?, 
        Etat_BL = ?, 
        Date_Signer = ?, 
        Date_Depot = ?, 
        valider = ?,
        commantaireActuel = ?, 
        commantaireRecuperer = ?, 
        Piece_jointe = ?,
        type_piece = ?
        WHERE ID_BL = ?";

    $stmt = $mysqli->prepare($query);

    $stmt->bind_param("sssssssssssssss", $ID_CLIENT, $dateBl2, $id_utilis, $date_retour, $etat, $dete_signe, $date_depo, $valider, $nouveauCommentaire, $COMMANTAIRE_actuelle, $commantaireRecupere, $file_name, $fileType, $blNumber);

    $result = $stmt->execute();

    $stmt->close();

    return $result;
}


function insertBL($id_bl, $id_client, $dateBl2, $id_utilis, $date_retour, $file_name, $etat, $dete_signe, $date_depo, $fileType, $command, $COMMANTTAIRE){
    global $mysqli;
    $insert_sql = "INSERT INTO bl (ID_BL, ID_Client, Date_bl, ID_utilisateur, Date_Retour, Piece_jointe, Etat_BL, Date_Signer, Date_Depot, type_piece, Num_Commande, commentaireSigner) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
   
    $stmt = $mysqli->prepare($insert_sql);
   
    $stmt->bind_param("ssssssssssss", $id_bl, $id_client, $dateBl2, $id_utilis, $date_retour, $file_name, $etat, $dete_signe, $date_depo, $fileType, $command, $COMMANTTAIRE);

    $result = $stmt->execute();
   
    $stmt->close();

    return $result;
}

function getAllblnotvalide(){
    $query2 = "SELECT * FROM bl where valider=false ";
    global $mysqli;
    $result2 = $mysqli->query($query2);
    
    return $result2;
}
function getAllblvalide(){
    $query2 = "SELECT * FROM bl where valider=true ";
    global $mysqli;
    $result2 = $mysqli->query($query2);
    
    return $result2;
}
function connecter($user,$password){
    $query2 = "SELECT * FROM utilisateur where nom_utilisateur='$user' and password='$password' ";
    global $mysqli;
    $result2 = $mysqli->query($query2);

    return $result2;
}
function enregistrerTraçabilité($id_bl, $Type_mouvement, $Date_mouvement, $ID_utilisateur) {
    global $mysqli; // Assurez-vous que vous avez une connexion à la base de données globale

    // Échappez les valeurs pour éviter les injections SQL
    $id_bl = mysqli_real_escape_string($mysqli, $id_bl);
    $Type_mouvement = mysqli_real_escape_string($mysqli, $Type_mouvement);
    $Date_mouvement = mysqli_real_escape_string($mysqli, $Date_mouvement);
    $ID_utilisateur = mysqli_real_escape_string($mysqli, $ID_utilisateur);

    // Créez la requête SQL pour insérer les données de traçabilité
    $query = "INSERT INTO traçabilité (id_bl, Type_mouvement, Date_mouvement, ID_utilisateur) 
              VALUES ('$id_bl', '$Type_mouvement', '$Date_mouvement', '$ID_utilisateur')";

    // Exécutez la requête SQL et retournez le résultat
    $result2 = $mysqli->query($query);

    return $result2;
}
function checkBl($blNumber) {
    global $mysqli;

    // Échappez la valeur de l'ID_BL pour éviter les injections SQL
    $blNumber = mysqli_real_escape_string($mysqli, $blNumber);

    // Créez la requête SQL pour vérifier l'existence de l'ID_BL
    $query = "SELECT ID_BL FROM bl WHERE ID_BL = '$blNumber'";

    // Exécutez la requête SQL
    $result = $mysqli->query($query);

    // Vérifiez si des résultats sont retournés (l'ID_BL existe)
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}


function modifierTraçabilité($id_bl, $id_utilis, $type_mouvement, $date_mouvement) {
    global $mysqli;

    // Échappez les valeurs pour éviter les injections SQL
    $id_bl = mysqli_real_escape_string($mysqli, $id_bl);
    $id_utilis = mysqli_real_escape_string($mysqli, $id_utilis);
    $type_mouvement = mysqli_real_escape_string($mysqli, $type_mouvement);
    $date_mouvement = mysqli_real_escape_string($mysqli, $date_mouvement);

    // Créez la requête SQL pour mettre à jour les données de traçabilité
    $query = "UPDATE traçabilité 
              SET Type_mouvement = '$type_mouvement', Date_mouvement = '$date_mouvement', ID_utilisateur = '$id_utilis' 
              WHERE id_bl = '$id_bl'";

    // Exécutez la requête SQL et retournez le résultat
    $result2 = $mysqli->query($query);

    return $result2;
}


function modifierbl1($blNumber, $ID_CLIENT, $dateBl2, $id_utilis, $dete_signe, $date_depo, $date_retour, $etat) {
    global $mysqli;
    
    // Prepare the SQL statement with placeholders
    $query = "UPDATE bl SET 
    ID_Client = ?, 
    Date_bl = ?, 
    ID_utilisateur = ?, 
    Date_Retour = ?, 
    Etat_BL = ?, 
    Date_Signer = ?, 
    Date_Depot = ? 
    WHERE ID_BL = ?";

    // Create a prepared statement
    $stmt = $mysqli->prepare($query);

    // Bind the parameters
    $stmt->bind_param("ississsi", $ID_CLIENT, $dateBl2, $id_utilis, $date_retour, $etat, $dete_signe, $date_depo, $blNumber);

    // Execute the statement
    $result = $stmt->execute();
    $stmt->close();

    return $result;
}


// Fonction pour supprimer un BL en fonction de son numéro
function supprimerBL($blNumber) {
    global $mysqli; // Assurez-vous que votre connexion MySQL est disponible ici

    
    $sql = "DELETE FROM bl WHERE ID_BL = ?";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $blNumber); 

        if ($stmt->execute()) {
            $stmt->close();
            return true; // La suppression a réussi
        } else {
            return false; // Erreur lors de l'exécution de la requête
        }
    } else {
        return false; // Erreur lors de la préparation de la requête
    }
}
function getdetail($num_bl){
    $query2 = "SELECT dt.*,art.* FROM details_bl dt,article art WHERE dt.ref=art.ref and dt.ID_BL= '$num_bl'";
    global $mysqli;
    $result2 = $mysqli->query($query2);
    
    return $result2;
}
function ajouterDetail($ref, $id_bl, $qte){
    global $mysqli;
    
    // Étape 2 : Utilisez le nouveau ID_BL pour insérer des détails de l'article
    $query2 = "INSERT INTO details_bl (Ref, ID_BL, Quantité_livrer) VALUES (?, ?, ?)";
    $stmt2 = $mysqli->prepare($query2);
    
    if ($stmt2) {
        $stmt2->bind_param("ssi", $ref, $id_bl, $qte); // Utilisez "ssi" pour indiquer que les types de données sont respectivement une chaîne, un entier et un entier.
        $stmt2->execute();
        $stmt2->close();
    } else {
        echo "Erreur dans la préparation de la requête SQL : " . $stmt2->error;
    }
}
function getArticleByRef($ref){
    $query2 = "SELECT * FROM article where ref='$ref'";
    global $mysqli;
    $result2 = $mysqli->query($query2);

    return $result2;
}
function getAllArticles(){
    $query2 = "SELECT * FROM article";
    global $mysqli;
    $result2 = $mysqli->query($query2);

    return $result2;
}

 



