
<?php include_once('../../templetes/header.php'); ?>

<script src="../../js/detail.js"></script>
<link href="../../css/BL.css" rel="stylesheet" >





<link href="../../css/detail.css" rel="stylesheet" >
    





                    <form  >
                    <div class="container mt-5 px-2">
    
    <div class="mb-2 d-flex justify-content-between align-items-center">
        
        <div class="position-relative"><span class="position-absolute search"><i class="fa fa-search"></i></span>
        <span class="position-absolute search"><i class="fa fa-search"></i></span>
            <label class="form-control-label px-3"><span class="text-danger"> </span></label>
            
            <input type="text" class="form-control w-100" placeholder="Search by Num BL"  name="rechercheTxt" id="rechercheTxt" onblur='submit();'>
            
            
        </div>
        
        
        
    </div>
    <div class="table-responsive">
    <table class="table table-striped" id="recherchtab">
        <thead class="thead-dark">
        <tr class="bg-light">
          <th scope="col" width="5%">
          <th scope="col" width="20%">NUM_BL</th>
          <th scope="col" width="10%">Ref Article</th>
          <th scope="col" width="20%">Qte Livrer</th>
        </tr>
      </thead>
 
  <?php

include_once '../../dao/dao.php';



  
$blNumber="";

if (isset($_REQUEST['rechercheTxt'])) {
    $blNumber = $_REQUEST['rechercheTxt'];
    echo "numBL $blNumber";
   
    // Requête SQL pour rechercher les données de Détail BL par numéro BL
    $result = getDetailBl($blNumber);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr class="custom-row">
            <th scope="row"><input class="form-check-input" type="checkbox"></th>
            <td>' . $row['ID_BL'] . '</td>
            
            <td>' . $row['Ref'] . '</td>
            <td>' . $row['Quantité_livrer'] . '</td>
        </tr>';
        }
    } else {
        // Aucun résultat trouvé, ajoutez une classe CSS spéciale
        echo '<tr class="no-results"><td colspan="5">Aucun résultat trouvé.</td></tr>';
    }

    // Fermez la connexion à la base de données
    $mysqli->close();
}


?>

   
  </tbody>
</table>
<script>
document.addEventListener("DOMContentLoaded", function () {
    var input = document.getElementById("rechercheTxt");
    var messageAucunResultat = document.getElementById("messageAucunResultat");
    var table = document.getElementById("recherchtab");

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
  </div>
    
</div>
                        
</div>
</form>
<?php include_once('../../templetes/footer.php'); ?>
