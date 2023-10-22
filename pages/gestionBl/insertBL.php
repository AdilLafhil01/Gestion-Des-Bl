



<?php
// Incluez votre code de connexion à la base de données ici
include_once './includes/config.php';
echo 'Problème avec le fichier.';
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

if (isset($_POST["submit"])) {
    if ($_FILES['PIECE_JOINDRE']['error'] != 0) {
        
    } else {
        $ID_BL = $_POST['ID_BL'];
        $ID_CLIENT = $_POST['ID_CLIENT'];
        $DateBl = $_POST['DateBl'];
        $ID_UTILISATEUR = $_POST['ID_UTILISATEUR'];
        $DATE_SIGNER = $_POST['DATE_SIGNER'];
        $Date_Depot = $_POST['DATE_DEPOT'];
        $DATE_RETOUR = $_POST['DATE_RETOUR'];
        $REF_ARTICLE = $_POST['REF_ARTICLE'];
        $ETAT_BL = $_POST['ETAT_BL'];

        $file_name = $_FILES["PIECE_JOINDRE"]["name"];
        $file_tmp = $_FILES["PIECE_JOINDRE"]["tmp_name"];
        $fileType = $_FILES["PIECE_JOINDRE"]["type"];
        if ($pdf_blob = fopen($file_tmp, "rb")) {
            try {
                
                // Insérez le nom du fichier dans la base de données
                $insert_sql = "INSERT INTO bl (ID_BL, ID_Client, Date_bl, ID_utilisateur, Ref, Date_Retour, Piece_jointe, Etat_BL, Date_Signer, Date_Depot, type_piece) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
                
                $stm = $mysqli->prepare($insert_sql);
                $result = $mysqli->query($query);
                $stm->bind_param('sssssssssss', $ID_BL, $ID_CLIENT, $DateBl, $ID_UTILISATEUR, $REF_ARTICLE, $DATE_RETOUR, $file_name, $ETAT_BL, $DATE_SIGNER, $Date_Depot, $fileType);

                if ($stm->execute() === false) {
                    echo 'Impossible de sauvegarder les informations dans la base de données.';
                } else {
                    // Définissez le chemin du dossier où vous souhaitez enregistrer le fichier
                    $upload_directory = "C:/Piece Joindre BL/";

                    // Définissez le chemin complet du fichier sur le serveur
                    $target_path = $upload_directory . $file_name;

                    // Utilisez move_uploaded_file() pour déplacer le fichier vers le dossier cible
                    if (move_uploaded_file($file_tmp, $target_path)) {
                        echo 'Fichier téléchargé et enregistré avec succès dans le dossier.';
                    } else {
                        echo 'Impossible de déplacer le fichier vers le dossier.';
                    }

                    // Fermez la connexion à la base de données
                    $mysqli->close();
                }
            } catch (PDOException $e) {
                echo 'Database Error ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
            }
        } else {
            echo 'Impossible d\'ouvrir le fichier PDF attaché.';
        }
    }
} else {
    header('location:choose_file.php');
}
?>
