<?php 
global $page;


if(isset($_GET['page'])){
    //$_GET['page'];
    $page="./pages/gestionBl/GestionBl.php";
  // $_POST['page'];
  // echo  $_POST['page'];
  //header('location:#');
  //http_build_query($page);
  header('location:home.php');
  

}else{
    $page="./pages/dashBoard/dashBoard.php";
}

?>