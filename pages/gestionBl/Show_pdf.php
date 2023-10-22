<!DOCTYPE html>
<html>
<head>
    <!-- Autres balises d'en-tête -->
</head>
<body>
    <!-- Votre contenu HTML ici -->
    
    <embed src="<?php echo $file_path; ?>" type="application/pdf" width="100%" height="500px" />
    
    <!-- Autres éléments HTML et scripts -->
</body>
</html>








<?php include_once('pdfViewer.php')?>



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
							  <iframe src="../../upload/pdfFiles/Report2.pdf"   style="position: absolute;height: 85%; width:95%; border: none" ></iframe>
							  </div>
						  </div>
					  </form>
				  </div>
			  </dialog>
              <div class="input-group-append">
                <button type="button" class="bi bi-eye" onClick='dialog.show()'>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"></path>
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"></path>
                    </svg>
                </button>
            </div>

<?php

// Récupérez l'identifiant du BL à partir de la requête GET
if (isset($_GET['ID_BL'])) {
    $ID_BL = $_GET['ID_BL'];

    // Incluez votre code de connexion à la base de données ici
    include_once '../../dao/dao.php';

    // Requête SQL pour récupérer le nom du fichier en fonction de l'ID
    $sql = "SELECT Piece_jointe FROM bl WHERE ID_BL = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('s', $ID_BL);
    $stmt->execute();

    // Récupérer le résultat
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Récupérez le nom du fichier
        $file_name = $row['Piece_jointe'];

        // Construisez le chemin complet vers le fichier sur le serveur
        $file_path = "C:/Piece Joindre BL/" . $file_name;
        
        // Vérifiez si le fichier existe
        if (file_exists($file_path)) {
            
            // Définissez les en-têtes appropriés pour le type de contenu
            header("Content-Type: application/pdf.pdf");
            header("Content-Disposition: inline; filename=" . $file_name);
            
            // Affichez le contenu du fichier PDF
            readfile($file_path);
           
            
        } else {
           
            echo "Fichier non trouvé.";
        }
    } else {
        echo "ID_BL non trouvé dans la base de données.";
    }

    $stmt->close();
    // Fermez la connexion à la base de données
    $mysqli->close();
} else {
    echo "ID_BL manquant.";
}
?>
