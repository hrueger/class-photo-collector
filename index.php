<?php require_once("partials/head.php"); ?>

<div class="jumbotron">
  <h1>AG Klassenfotos 2021</h1>
  <p class="lead">Hier kommt noch ein sinnvoller Text hin.</p>
  <?php if (isLoggedin()) {
    ?>
    <h4>Welcome <?php echo $_SESSION["username"] ?>!</h4>
    <b>You are a <?php echo $_SESSION["job"] ?>.</b>
    <p>Use the navigation bar at the top of the page to get started.</p>
    <a href="signout.php">Abmelden</a>
  <?php } else { ?>
    <p>Bitte einloggen:</p>
    <a href="signin.php" class="btn btn-primary btn-large">Mit Microsoft Teams anmelden</a>
    <?php } ?>
</div>

<?php require_once("partials/foot.php"); ?>