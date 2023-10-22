<?php include_once('../../templetes/header.php'); ?>
<div class="col fw-bold fs-2 text">
    <?php
    $row = $_SESSION["utilisateur"];
    echo "Bonjour " . $row['Role'];
    ?>
</div>
<div class="container">
    <div class="row">
        <?php
        if ($row['Role'] === "admin") {
            include_once('dashBoardAdmin.php');
        } elseif ($row['Role'] === "user") {
            include_once('dashBoardUser.php');
        } elseif ($row['Role'] === "validateur") {
            include_once('dashBoardValidateur.php');
        }
        ?>
    </div>
</div>

<?php include_once('../../templetes/footer.php')  ?>