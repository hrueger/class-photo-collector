<?php require_once("partials/head.php"); ?>

<div class="jumbotron">
  <h1>AG Klassenfotos 2021</h1>
  <p class="lead mb-5">Portraitfotos der Schüler für den Jahresbericht 2021 hochladen</p>
  <?php if (isLoggedin()) {
  ?>
    <h4>Willkommen <?php echo $_SESSION["username"] ?>!</h4>
    
    <?php if ($_SESSION["job"] == "Schueler") {
      ?>
      <div class="card p-3 mb-3"><span>
        Da wir dieses Jahr keine Klassenfotos für den Jahresbericht machen konnten, bitten wir Dich, <b>bis zum Mittwoch, 31.03.2021</b>, ein Portraitfoto von Dir hochzuladen.
        </span></div>
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
          Dein Portraitfoto und deine Einverständniserklärung wurden erfolgreich hochgeladen. Es wird nun überprüft. Dies kann durchaus einige Tage dauern.
        </div>
      <?php } else if (getMyPhotoState() == $PHOTO_STATES["ACCEPTED"]) { ?>
        <div class="alert alert-success">
          <b>Status: Akzeptiert</b><br>
          Dein Portraitfoto wurde akzeptiert. Danke für's Hochladen!
        </div>
      <?php } else if (getMyPhotoState() == $PHOTO_STATES["PHOTO_REJECTED"]) { ?>
        <div class="alert alert-danger">
          <b>Status: Portraitfoto abgelehnt</b><br>
          Dein Portraitfoto wurde leider nicht akzeptiert. Lade es erneut hoch und beachte die angegebenen Kriterien!<br>
          <ul>
            <li>Bildgröße zwischen 0.5 MB und 10 MB</li>
            <li>Möglichst heller, einfarbiger Hintergrund</li>
            <li>Gesicht gut erkennbar, Blick gerade aus. Bitte lächeln!</li>
          </ul>
          <a href="upload.php" class="btn btn-outline-success mt-3">Foto hochladen</a>
        </div>
      <?php } else if (getMyPhotoState() == $PHOTO_STATES["PRIVACY_REJECTED"]) { ?>
        <div class="alert alert-danger">
          <b>Status: Einverständniserklärung abgelehnt</b><br>
          Du hast die Einverständniserklärung nicht korrekt ausgefüllt. Lade das Foto der Einverständniserklärung vollständig ausgefüllt und unterschrieben sowie dein Portraitfoto erneut hoch! <br>
          <a href="upload.php" class="btn btn-outline-success mt-3">Foto hochladen</a>
        </div>
      <?php } else if (getMyPhotoState() == $PHOTO_STATES["BOTH_REJECTED"]) { ?>
        <div class="alert alert-danger">
          <b>Status: Portraitfoto und Einverständniserklärung abgelehnt</b><br>
          Dein Portraitfoto wurde leider nicht akzeptiert. Lade es erneut hoch und beachte die angegebenen Kriterien!<br>
          <ul>
            <li>Bildgröße zwischen 0.5 MB und 10 MB</li>
            <li>Möglichst heller, einfarbiger Hintergrund</li>
            <li>Gesicht gut erkennbar, Blick gerade aus. Bitte lächeln!</li>
          </ul>
          Außerdem hast du die Einverständniserklärung nicht korrekt ausgefüllt. Lade das Foto der Einverständniserklärung vollständig ausgefüllt und unterschrieben sowie dein Portraitfoto erneut hoch! <br>
          <a href="upload.php" class="btn btn-outline-success mt-3">Foto hochladen</a>
        </div>
      <?php } ?>
    <br>
    <p>Bei technischen Problemen melde Dich über Teams bei Herrn Herz.</p>
    <?php } else if ($_SESSION["job"] == "Lehrer") { ?>
      Klicken Sie im Menü oben auf <i>Klassen</i>, um die Fotos Ihrer Klasse anzusehen.
    <br>
    <p>Bei technischen Problemen melden Sie sich bitte über Teams bei Herrn Herz.</p>
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