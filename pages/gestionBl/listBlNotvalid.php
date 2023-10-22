




<?php include_once('../../templetes/header.php'); 
   
?>

<script src="../../js/detail.js"></script>
<link href="../../css/BL.css" rel="stylesheet" >
<link href="../../css/detail.css" rel="stylesheet" >







                    <div class="container mt-5 px-2">
    
    <div class="mb-2 d-flex justify-content-between align-items-center">
        
        <div class="position-relative"><span class="position-absolute search"><i class="fa fa-search"></i></span>
        <span class="position-absolute search"><i class="fa fa-search"></i></span>
            <label class="form-control-label px-3"><span class="text-danger"> </span></label>
            
            <input type="text" class="form-control w-100" placeholder="Search by Num BL" id="rechercheTxt" />
            
            
        </div>
        
        
      
    </div>
    <div class="table-responsive">
    <table class="table table-striped" id="tablerecherch">
        <thead class="thead-dark">
        <tr class="bg-light">
          <th scope="col" width="20%">NUM_BL</th>
          <th scope="col" width="10%">Client</th>
          <th scope="col" width="20%">Date Depot</th>
          <th scope="col" width="20%">Etat</th>

        </tr>
      </thead>
 <tbody>
 <div id="messageAucunResultat" class="no-results">Aucun résultat trouvé.</div>

    <?php
    include_once('../../dao/dao.php');

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }




global $result;
    $result=getAllblnotvalide();

 if ($result->num_rows > 0) {
   
        while ($row = $result->fetch_assoc()) {
           // $tab[]=$row;
            echo '<tr class="custom-row">
           
            <td> <a href=BLetDETAIL.php?rechercheTxt='.$row["ID_BL"].'>'. $row["ID_BL"] . '</a></td>
            <td>' . $row["ID_Client"] . '</td>
            <td>' . $row["Date_Depot"] . '</td>
            <td>' . $row["Etat_BL"] . '</td>
        </tr>';
        }
    } else {
        // Aucun résultat trouvé, ajoutez une classe CSS spéciale
        echo '<tr class="no-results"><td colspan="5">Aucun résultat trouvé.</td></tr>';
    }
    ?>
 </tbody>
 <script>
document.addEventListener("DOMContentLoaded", function () {
    var input = document.getElementById("rechercheTxt");
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




      <?php include_once('../../templetes/footer.php'); ?>