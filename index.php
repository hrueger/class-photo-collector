<?php require_once("partials/head.php"); ?>

<div class="jumbotron">
  <h1>AG Klassenfotos 2021</h1>
  <p class="lead mb-5">Das Sammeltool für Klassenfotos</p>
  <?php if (isLoggedin()) {
  ?>
    <h4>Willkommen <?php echo $_SESSION["username"] ?>!</h4>

    <?php if ($_SESSION["job"] == "Schueler") { ?>
      <?php if ($_SESSION["photo_state"] == $PHOTO_STATES["MISSING"]) { ?>
        <div class="alert alert-primary">
          <b>Status: Nicht hochgeladen</b><br>
          Du hast bisher noch kein Foto hochgeladen. Hole das jetzt nach: <br>
          <a href="upload.php" class="btn btn-outline-success mt-3">Foto hochladen</a>
        </div>
        <?php } else if ($_SESSION["photo_state"] == $PHOTO_STATES["UPLOADED"]) { ?>
        <div class="alert alert-warning">
          <b>Status: Warten auf Überprüfung</b><br>
          Dein Foto wurde erfolgreich hochgeladen. Es wird nun überprüft. Dies kann durchaus einige Tage dauern.
        </div>
      <?php } ?>
    <?php } else if ($_SESSION["job"] == "Lehrer") { ?>

    <?php } else { ?>
      Nur LehrerInnen und SchülerInnen können dieses Tool benutzen!
    <?php } ?>
    <br>
    <a class="btn btn-outline-primary mt-5" href="signout.php">Abmelden</a>
  <?php } else { ?>
    <p>Bitte einloggen:</p>
    <a href="signin.php" class="btn btn-primary btn-large">Mit Microsoft Teams anmelden</a>
  <?php } ?>
</div>

<?php require_once("partials/foot.php"); ?>