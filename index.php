<?php require_once("partials/head.php"); ?>

<div class="jumbotron">
  <h1>Splitter Fotoupload 2021</h1>
  <p class="lead mb-5">Fotos für den Splitter hochladen</p>
  <?php if (isLoggedin()) {
  ?>
    <h4>Willkommen <?php echo $_SESSION["username"] ?>!</h4>
    
    <?php if ($_SESSION["job"] == "Schueler" || $_SESSION["job"] == "Lehrer") {
      ?>
      <div class="card p-3 mb-3">
        <p>Wir fahren gemeinsam nach Tokyo!</p>
        <p>Liebe Schüler*innen, Liebe Lehrkräfte, Liebe Eltern, </p>
        <p>Wie bereits in einem Brief der Schulleitung mitgeteilt wurde, findet aktuell das Projekt "Road to Tokyo" statt. Gemeinsam versuchen wir, eine Strecke bis nach Tokyo zu radeln, zu laufen oder anders zu erreichen. </p>
        <p>Wir, also das Team der Schülerzeitung und das P-Seminar, wollen für die kommende Ausgabe eine Collage zu unserer Aktion gestalten. Hierfür bitten wir euch und Sie, uns lustige, schöne, interessante und verrückte Bilder von euren/Ihren Touren zu schicken. Wir freuen uns auf tolle und kreative Fotos!</p>
        <p>Diese könnt ihr hier hochladen. </p>
        <p>Die entstandene Collage könnt Ihr/können Sie dann in der Ausgabe des Splitters, die voraussichtlich zum Schuljahresende erscheinen wird, entdecken. </p>
        <p>Vielen Dank für eure/Ihre Mitarbeit! </p>
        <p>Euer Team der Schülerzeitung, das P-Seminar Deutsch und Herr Born</p>
      </div>
      <?php
      if (getMyPhotoState() == $PHOTO_STATES["MISSING"]) { ?>
        <div class="alert alert-primary">
          <b>Status: Nicht hochgeladen</b><br>
          Du hast bisher noch kein Foto hochgeladen. Hole das jetzt nach: <br>
          <a href="upload.php" class="btn btn-outline-success mt-3">Foto hochladen</a>
        </div>
      <?php } else if (getMyPhotoState() == $PHOTO_STATES["UPLOADED"]) { ?>
        <div class="alert alert-warning">
          <b>Status: Warten auf Überprüfung</b><br>
          Dein Foto und deine Einverständniserklärung wurden erfolgreich hochgeladen. Es wird nun überprüft. Dies kann durchaus einige Tage dauern.
        </div>
      <?php } else if (getMyPhotoState() == $PHOTO_STATES["ACCEPTED"]) { ?>
        <div class="alert alert-success">
          <b>Status: Akzeptiert</b><br>
          Dein Foto wurde akzeptiert. Danke für's Hochladen!
        </div>
      <?php } else if (getMyPhotoState() == $PHOTO_STATES["PHOTO_REJECTED"]) { ?>
        <div class="alert alert-danger">
          <b>Status: Foto abgelehnt</b><br>
          Dein Foto wurde leider nicht akzeptiert. Lade es erneut hoch und beachte die angegebenen Kriterien!
          <a href="upload.php" class="btn btn-outline-success mt-3">Foto hochladen</a>
        </div>
      <?php } else if (getMyPhotoState() == $PHOTO_STATES["PRIVACY_REJECTED"]) { ?>
        <div class="alert alert-danger">
          <b>Status: Einverständniserklärung abgelehnt</b><br>
          Du hast die Einverständniserklärung nicht korrekt ausgefüllt. Lade das Foto der Einverständniserklärung vollständig ausgefüllt und unterschrieben sowie dein Foto erneut hoch! <br>
          <a href="upload.php" class="btn btn-outline-success mt-3">Foto hochladen</a>
        </div>
      <?php } else if (getMyPhotoState() == $PHOTO_STATES["BOTH_REJECTED"]) { ?>
        <div class="alert alert-danger">
          <b>Status: Foto und Einverständniserklärung abgelehnt</b><br>
          Dein Foto wurde leider nicht akzeptiert. Lade es erneut hoch und beachte die angegebenen Kriterien!<br>
          Außerdem hast du die Einverständniserklärung nicht korrekt ausgefüllt. Lade das Foto der Einverständniserklärung vollständig ausgefüllt und unterschrieben sowie dein Foto erneut hoch! <br>
          <a href="upload.php" class="btn btn-outline-success mt-3">Foto hochladen</a>
        </div>
      <?php } ?>
    <br>
    <?php } else { ?>
      Nur LehrerInnen und SchülerInnen können dieses Tool benutzen!
    <?php } ?>
    <a class="btn btn-outline-primary mt-5" href="signout.php">Abmelden</a>
  <?php } else { ?>
    <p>Bitte einloggen:</p>
    <a href="signin.php" class="btn btn-primary btn-large">Mit Microsoft Teams anmelden</a>
  <?php } ?>
</div>

<?php require_once("partials/foot.php"); ?>