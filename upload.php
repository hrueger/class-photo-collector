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
    $error .= "Du musst ein Foto hochladen! ";
  }
  if (!$_FILES["privacy"]["name"]) {
    $error .= "Du musst ein Foto der Datenschutzerklärung hochladen!";
  }

  if ($error == "") {
    $username = str_replace("@allgaeugymnasium.onmicrosoft.com", "", $_SESSION["email"]);
    $username = str_replace(".", " ", $username);

    $filetype_photo = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
    $target_photo = $target_dir . $username . " Foto." . $filetype_photo;
    $filetype_privacy = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
    $target_privacy = $target_dir . $username . " Datenschutzerklaerung." . $filetype_privacy;

    if ($_FILES["photo"]["size"] > 1024**3) {
      $error .= "Dein Bild darf maximal 1 MB groß sein.";
    }
    if ($_FILES["privacy"]["size"] > 1024**3) {
      $error .= "Deine Datenschutzerklärung darf maximal 1 MB groß sein.";
    }
    if (!in_array($filetype_photo, array("png", "jpg", "jpeg", "gif", "pdf"))) {
      $error .= "Für dein Bild sind nur PNG, JPG, JPEG, GIF und PDF Dateien erlaubt.";
    }
    if (!in_array($filetype_privacy, array("png", "jpg", "jpeg", "gif", "pdf"))) {
      $error .= "Für dein Bild sind nur PNG, JPG, JPEG, GIF und PDF Dateien erlaubt.";
    }
  }

  if ($error == "") {
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_photo) && move_uploaded_file($_FILES["privacy"]["tmp_name"], $target_privacy)) {
      $_SESSION["photo_state"] = $PHOTO_STATES["UPLOADED"];
      
      $statement = $db->prepare("UPDATE users SET photo_state = ? WHERE id = ?");
      $statement->execute(array($PHOTO_STATES["UPLOADED"], $_SESSION["id"]));
      redirect("index.php");
    } else {
      $error = "Die Datei konnte nicht gespeichert werden.";
    }
  }
}



?>




<div class="jumbotron">
  <h1>Foto hochladen</h1>
  <p>Dein Foto muss:</p>
  <ul>
    <li>So sein</li>
    <li>So sein</li>
    <li>So sein</li>
    <li>So sein</li>
    <li>So sein</li>
  </ul>
  <p>Außerdem musst Du ein Foto der ausgefüllten Datenschutzerklärung hochladen. Die findest Du hier: (noch kein Link).</p>

  <?php if ($error) { ?>
    <div class="alert alert-danger">
      <b>Fehler:</b><br><?php echo htmlspecialchars($error); ?>
    </div>
  <?php } ?>


  <form method="POST" enctype="multipart/form-data">
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text">Portait:</span>
      </div>
      <div class="custom-file">
        <input type="file" class="custom-file-input" name="photo" id="photoInput">
        <label class="custom-file-label" for="photoInput">Datei wählen</label>
      </div>
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text">Datenschutzerklärung:</span>
      </div>
      <div class="custom-file">
        <input type="file" class="custom-file-input" name="privacy" id="privacyInput">
        <label class="custom-file-label" for="privacyInput">Datei wählen</label>
      </div>
    </div>
    <input type="submit" name="submit" class="btn btn-outline-success" value="Hochladen">
  </form>
</div>

<?php require_once("partials/foot.php"); ?>