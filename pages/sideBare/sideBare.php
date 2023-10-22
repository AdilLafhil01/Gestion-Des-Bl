
<nav  class="navbar navbar-inverse fixed-top" id="sidebar-wrapper" role="navigation">
     <ul class="nav sidebar-nav">
       <div class="sidebar-header">
       <div class="sidebar-brand">
         <a href="#"><?php  if(session_status() !== PHP_SESSION_ACTIVE) session_start(); $row=$_SESSION["utilisateur"] ;
            print_r( $row['Nom_utilisateur']);?></a></div></div>
      <?php if ($row['Role'] === "admin") {
    include_once('menuAdmin.php');
} elseif ($row['Role'] === "validateur") {
    include_once('menuValidateur.php');
} else {
    include_once('menuUser.php');
}
  ?>
      <li><a href="../../logout.php#home" >Log Out</a></li>
      
      </ul>
  </nav>