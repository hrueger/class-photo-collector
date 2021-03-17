<?php require_once("partials/head.php"); ?>
<?php ensureLoggedin() ?>
<?php ensureStudent() ?>

<?php ensureCanUpload(); ?>

<?php
$error = "";
if (isset($_POST["submit"])) {

  $target_dir = "userdata/" . $_SESSION["class"] . "/";
  if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
  }
  if (!$_FILES["photo"]["name"]) {
    $error .= " Du musst ein Foto hochladen! ";
  }
  if (!$_FILES["privacy"]["name"]) {
    $error .= " Du musst ein Foto der Einverständniserklärung hochladen!";
  }

  if ($error == "") {
    $safeUsername = getMySafeUsername();

    $filetype_photo = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
    $target_photo = $target_dir . $safeUsername . " Foto." . $filetype_photo;
    $filetype_privacy = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
    $target_privacy = $target_dir . $safeUsername . " Einverstaendniserklaerung." . $filetype_privacy;

    if ($_FILES["photo"]["size"] > 10*(1024**2) || $_FILES["photo"]["size"] < 1024**2) {
      $error .= " Dein Portraitfoto muss zwischen 1 MB und 10 MB groß sein.";
    }
    if ($_FILES["privacy"]["size"] > 10*(1024**2) || $_FILES["privacy"]["size"] < 1024**2) {
      $error .= " Deine Einverständniserklärung muss zwischen 1 MB und 10 MB groß sein.";
    }
    if (!in_array($filetype_photo, array("png", "jpg", "jpeg", "gif"))) {
      $error .= " Für Dein Portraitfoto sind nur PNG, JPG, JPEG und GIF Dateien erlaubt.";
    }
    if (!in_array($filetype_privacy, array("png", "jpg", "jpeg", "gif"))) {
      $error .= " Für Deine Einverständniserklärung sind nur PNG, JPG, JPEG und GIF Dateien erlaubt.";
    }
  }

  if ($error == "") {
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_photo) && move_uploaded_file($_FILES["privacy"]["tmp_name"], $target_privacy)) {
      
      $statement = $db->prepare("UPDATE users SET photo_state = ? WHERE id = ?");
      $statement->execute(array($PHOTO_STATES["UPLOADED"], $_SESSION["id"]));
      redirect("index.php");
    } else {
      $error .= " Die Datei konnte nicht gespeichert werden.";
    }
  }
}

if (isset($_GET["submit"])) {
  if (!isset($_FILES["photo"]) || !isset($_FILES["privacy"])) {
    $error .= " Dein Portraitfoto und deine Einverständniserklärung müssen jeweils kleiner als 10 MB sein!";
  }
}

?>


<style>
.preview-img {
  max-height: 20rem;
  max-width: 100%;
  float: right;
  border: 5px solid white;
}
</style>

<div class="jumbotron">
  <h1>Foto hochladen</h1>
  <img class="img-responsive preview-img" src="assets/portrait.jpg">
  <p class="mt-4">Dein Portraitfoto muss:</p>
  <ul>
    <li>zwischen 1 MB und 10 MB groß sein,</li>
    <li>vor einem möglichst hellen, einfarbigem Hintergrund aufgenommen sein,</li>
    <li>ungefähr so aussehen wie das Beispielbild hier und</li>
    <li>keinen bestimmten Dateinamen tragen.</li>
  </ul>
  <p>Außerdem musst Du ein Foto der ausgefüllten Einverständniserklärung hochladen. Diese findest Du hier: <a href="assets/einverstaendniserklaerung_klassenfotoupload_2021.pdf" target="_blank">Einverständniserklärung herunterladen</a>.</p>

  <?php if ($error) { ?>
    <div class="alert alert-danger">
      <b>Fehler:</b><br><?php echo htmlspecialchars($error); ?>
    </div>
  <?php } ?>


  <form method="POST" enctype="multipart/form-data" action="upload.php?submit=true">
    <!--<div class="input-group mb-3">
      <div class="input-group-prepend">-->
        <span class="">Portraitfoto:&nbsp;&nbsp;&nbsp;</span>
      <!--</div>
      <div class="custom-file">-->
        <input type="file" class="" name="photo" id="photoInput">
        <!--<label class="custom-file-label" for="photoInput">Datei wählen</label>
      </div>
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">-->
        <br>
        <span class="">Einverständniserklärung:&nbsp;&nbsp;&nbsp;</span>
      <!--</div>
      <div class="custom-file">-->
        <input type="file" class="" name="privacy" id="privacyInput">
        <!--<label class="custom-file-label" for="privacyInput">Datei wählen</label>
      </div>
    </div>--><br>
    <input type="submit" name="submit" class="mt-3 btn btn-outline-success" value="Hochladen">
  </form>
</div>

<?php require_once("partials/foot.php"); ?>