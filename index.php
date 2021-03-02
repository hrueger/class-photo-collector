<?php require_once("partials/head.php"); ?>

<div class="jumbotron">
  <h1>AG Klassenfotos 2021</h1>
  <p class="lead mb-5">Das Sammeltool für Klassenfotos</p>
  <?php if (isLoggedin()) {
  ?>
    <h4>Willkommen <?php echo $_SESSION["username"] ?>!</h4>

    <?php if ($_SESSION["job"] == "Schueler") {
      if (getMyPhotoState() == $PHOTO_STATES["MISSING"]) { ?>
        <div class="alert alert-primary">
          <b>Status: Nicht hochgeladen</b><br>
          Du hast bisher noch kein Foto hochgeladen. Hole das jetzt nach: <br>
          <a href="upload.php" class="btn btn-outline-success mt-3">Foto hochladen</a>
        </div>
      <?php } else if (getMyPhotoState() == $PHOTO_STATES["UPLOADED"]) { ?>
        <div class="alert alert-warning">
          <b>Status: Warten auf Überprüfung</b><br>
          Dein Foto wurde erfolgreich hochgeladen. Es wird nun überprüft. Dies kann durchaus einige Tage dauern.
        </div>
      <?php } else if (getMyPhotoState() == $PHOTO_STATES["ACCEPTED"]) { ?>
        <div class="alert alert-success">
          <b>Status: Akzeptiert</b><br>
          Dein Foto wurde akzeptiert. Danke für's Hochladen!
        </div>
      <?php } else if (getMyPhotoState() == $PHOTO_STATES["PHOTO_REJECTED"]) { ?>
        <div class="alert alert-danger">
          <b>Status: Foto abgelehnt</b><br>
          Dein Foto wurde leider nicht akzeptiert. Lade es erneut hoch und beachte die angegebenen Kriterien! <br>
          <a href="upload.php" class="btn btn-outline-success mt-3">Foto hochladen</a>
        </div>
      <?php } else if (getMyPhotoState() == $PHOTO_STATES["PRIVACY_REJECTED"]) { ?>
        <div class="alert alert-danger">
          <b>Status: Datenschutzerklärung abgelehnt</b><br>
          Du hast die Datenschutzerklärung nicht korrekt ausgefüllt. Lade dein Foto und die Datenschutzerklärung erneut hoch und beachte die angegebenen Kriterien! <br>
          <a href="upload.php" class="btn btn-outline-success mt-3">Foto hochladen</a>
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