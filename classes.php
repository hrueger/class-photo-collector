<?php

require_once("lib.php");
ensureLoggedin();
ensureTeacher();

$rest_json = file_get_contents("php://input");
if ($rest_json !== "") {
  $_POST = json_decode($rest_json, true);
}
if (isset($_GET["class"])) {
  ensureCanViewPhotosOfClass($_GET["class"]);

  if (isset($_POST["userId"]) && isset($_POST["action"])) {
    if ($_POST["action"] == "accept") {
      $state = $PHOTO_STATES["ACCEPTED"];
    } else if ($_POST["action"] == "rejectPhoto") {
      $state = $PHOTO_STATES["PHOTO_REJECTED"];
    } else if ($_POST["action"] == "rejectPrivacy") {
      $state = $PHOTO_STATES["PRIVACY_REJECTED"];
    } else if ($_POST["action"] == "rejectBoth") {
      $state = $PHOTO_STATES["BOTH_REJECTED"];
    } else if ($_POST["action"] == "waiting") {
      $state = $PHOTO_STATES["UPLOADED"];
    }
    $statement = $db->prepare("UPDATE users SET photo_state = ? WHERE id = ?");
    $statement->execute(array($state, $_POST["userId"]));
    echo getPhotoStateHTML($state);
    die();
  }

  if (isset($_GET["deleteTeacherPhoto"]) && isset($_POST["username"])) {
    $target_dir = "userdata/" . $_GET["class"] . "/Lehrkraefte/";
    removeUserImages($target_dir, $_POST["username"], true);
    die();
  }

  $error = "";
  if (isset($_POST["submit"]) && isset($_POST["userId"])) {
    $target_dir = "userdata/" . $_GET["class"] . "/";
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
      $statement = $db->prepare("SELECT email FROM users WHERE id=?");
      $statement->execute(array($_POST["userId"]));
      $user = $statement->fetchAll()[0];
      $safeUsername = getSafeUsername($user["email"]);

      $filetype_photo = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
      $target_photo = $target_dir . $safeUsername . " Foto." . $filetype_photo;
      $filetype_privacy = strtolower(pathinfo($_FILES["privacy"]["name"], PATHINFO_EXTENSION));
      $target_privacy = $target_dir . $safeUsername . " Einverstaendniserklaerung." . $filetype_privacy;
    }

    if ($error == "") {
      removeUserImages($target_dir, $safeUsername, false);
      if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_photo) && move_uploaded_file($_FILES["privacy"]["tmp_name"], $target_privacy)) {

        $statement = $db->prepare("UPDATE users SET photo_state = ? WHERE id = ?");
        $statement->execute(array($PHOTO_STATES["UPLOADED"], $_POST["userId"]));
      } else {
        $error .= " Die Datei konnte nicht gespeichert werden.";
      }
    }
  }

  // Teacher Upload
  if (isset($_POST["submitTeacherImage"])) {
    $target_dir = "userdata/" . $_GET["class"] . "/Lehrkraefte/";
    if (!file_exists($target_dir)) {
      mkdir($target_dir, 0777, true);
    }
    if (!$_FILES["photo"]["name"]) {
      $error .= " Du musst ein Foto hochladen! ";
    }
    if ($error == "") {
      $safeUsername = getMySafeUsername();

      $filetype_photo = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
      $target_photo = $target_dir . $safeUsername . "." . $filetype_photo;
    }

    if ($error == "") {
      removeUserImages($target_dir, $safeUsername, true);
      if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $target_photo)) {
        $error .= " Die Datei konnte nicht gespeichert werden.";
      }
    }
  }
}
?>




<?php require_once("partials/head.php");

$classes = getClasses();

function getPhotoStateHTML($state)
{
  global $PHOTO_STATES;
  global $PHOTO_STATES_PRETTY;
  if ($state == $PHOTO_STATES["ACCEPTED"]) {
    echo "<span class='text-success'>" . $PHOTO_STATES_PRETTY[$state] . "</span>";
  } else if ($state == $PHOTO_STATES["UPLOADED"]) {
    echo "<span class='text-primary'>" . $PHOTO_STATES_PRETTY[$state] . "</span>";
  } else {
    echo "<span class='text-danger'>" . $PHOTO_STATES_PRETTY[$state] . "</span>";
  }
}

?>

<style>
  .fake-button {
    border-radius: 3px;
  }

  #modal-image {
    margin-left: auto;
    margin-right: auto;
    max-height: 70vh;
  }
</style>

<div class="jumbotron">

  <?php
  if (isset($_GET["class"])) {
    ensureCanViewPhotosOfClass($_GET["class"]);

    if (isset($_POST["userId"]) && isset($_POST["action"])) {
      if ($_POST["action"] == "accept") {
        $state = $PHOTO_STATES["ACCEPTED"];
      } else if ($_POST["action"] == "rejectPhoto") {
        $state = $PHOTO_STATES["PHOTO_REJECTED"];
      } else if ($_POST["action"] == "rejectPrivacy") {
        $state = $PHOTO_STATES["PRIVACY_REJECTED"];
      } else if ($_POST["action"] == "rejectBoth") {
        $state = $PHOTO_STATES["BOTH_REJECTED"];
      } else if ($_POST["action"] == "waiting") {
        $state = $PHOTO_STATES["UPLOADED"];
      }
      $statement = $db->prepare("UPDATE users SET photo_state = ? WHERE id = ?");
      $statement->execute(array($state, $_POST["userId"]));
      echo getPhotoStateHTML($state);
      die();
    }
  ?>
    <h1>Klasse <?php echo htmlspecialchars($_GET["class"]); ?>:</h1>
    <a class="btn btn-outline-primary" href="classes.php"><i class="fas fa-arrow-back"></i> Zurück</a>
    <div class="card p-2 my-3">
      <?php if ($error) { ?>
        <div class="alert alert-danger">
          <b>Fehler:</b><br>
          <?php echo $error; ?>
        </div>
      <?php } ?>
      <b>Anleitung:</b><br>
      <p>Liebe Klassenleiterin, lieber Klassenleiter,<br>bitte führen Sie bei jeder Schülerin / jedem Schüler ihrer Klasse folgende Schritte aus:</p>
      <ol>
        <li>Klicken Sie auf das <b>Portraitfoto</b> und überprüfen Sie die <b>Übereinstimmung von Name und Gesicht</b>.</li>
        <li>Klicken Sie auf die <b>Einverständniserklärung</b> und überprüfen Sie die <b>Vollständigkeit, vor allem die Unterschriften</b>.</li>
        <li>Sind Portraitfoto und Einverständniserklärung <b>in Ordnung</b>, klicken Sie auf <i class="fake-button fas fa-check text-success d-inline-block border border-success p-1"></i>.</li>
        <li>Ist das <b>Portrait nicht in Ordnung</b> (falsche Person, Gesicht nicht / schlecht erkennbar, ...), klicken Sie auf <i class="fake-button fas fa-user-times text-danger d-inline-block border border-danger p-1"></i>.</li>
        <li>Ist die <b>Einverständniserklärung nicht in Ordnung</b> (Unterschriften fehlen, sind nicht glaubhaft, ...), klicken Sie auf <i class="fake-button fas fa-file-alt text-danger d-inline-block border border-danger p-1"></i>.</li>
        <li>Ist <b>weder das Portraitfoto noch die Einverständniserklärung in Ordnung</b>, klicken Sie auf <i class="fake-button fas fa-times text-danger d-inline-block border border-danger p-1"></i>.</li>
        <li>Falls Sie Ihre <b>Eingaben rückgängig</b> machen wollen, klicken Sie auf <i class="fake-button fas fa-clock text-primary d-inline-block border border-primary p-1"></i>.</li>
      </ol>
      <div class="alert alert-warning">
        <b>Wichtig!</b>:<br>
        Bitte nehmen Sie im Fall von 4., 5. oder 6. mit dem Schüler über Teams Kontakt auf und erläutern Sie ihm seinen Fehler.<br>
        Bei wirklich unklaren Fällen, schreiben Sie bitte Herrn Herz unter Nennung der Klasse und des Schülernamens über Teams an.
      </div>
      Vielen Dank für Ihre Mitarbeit bei der Erstellung des Jahresberichts!
    </div>
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Status</th>
            <th>Portraitfoto</th>
            <th>Einverständniserklärung</th>
            <th>Aktionen</th>
          </tr>
        </thead>
        <tbody>
          <?php

          $statement = $db->prepare("SELECT * FROM users WHERE class=? ORDER BY email ASC");
          $statement->execute(array($_GET["class"]));
          $users = $statement->fetchAll();
          $c = 0;
          foreach ($users as $user) {
            $c++;
          ?>
            <tr>
              <td><?php echo $c; ?></td>
              <td><?php echo $user["username"]; ?></td>
              <td class="state">
                <?php
                echo getPhotoStateHTML($user["photo_state"]);
                ?>

              </td>
              <td><?php if ($user["photo_state"] != $PHOTO_STATES["MISSING"]) { ?><img class="d-block img-fluid userimg-small cursor-pointer" onclick="openModal('<?php echo $user['id']; ?>', '<?php echo $user['username']; ?>', 'photo')" src="serveImage.php?type=photo&userId=<?php echo $user["id"]; ?>&thumbnail"><?php } ?></td>
              <td><?php if ($user["photo_state"] != $PHOTO_STATES["MISSING"]) { ?><img class="d-block img-fluid userimg-small cursor-pointer" onclick="openModal('<?php echo $user['id']; ?>', '<?php echo $user['username']; ?>', 'privacy')" src="serveImage.php?type=privacy&userId=<?php echo $user["id"]; ?>&thumbnail"><?php } ?></td>
              <td>
                <?php if ($user["photo_state"] != $PHOTO_STATES["MISSING"]) { ?>

                  <button class="btn btn-outline-success" title="Portrait und Einverständniserklärung OK" onclick="submit('accept', '<?php echo $user["id"]; ?>');">
                    <i class="fas fa-check"></i>
                  </button>

                  <button class="btn btn-outline-danger" title="Portraitfoto nicht in Ordnung" onclick="submit('rejectPhoto', '<?php echo $user["id"]; ?>');">
                    <i class="fas fa-user-times"></i>
                  </button>

                  <button class="btn btn-outline-danger" title="Einverständniserklärung nicht in Ordnung" onclick="submit('rejectPrivacy', '<?php echo $user["id"]; ?>');">
                    <i class="fas fa-file-alt"></i>
                  </button>

                  <button class="btn btn-outline-danger" title="Portraitfoto und Einverständniserklärung nicht in Ordnung" onclick="submit('rejectBoth', '<?php echo $user["id"]; ?>');">
                    <i class="fas fa-times"></i>
                  </button>

                  <button class="btn btn-outline-primary" title="Status zurücksetzen" onclick="submit('waiting', '<?php echo $user["id"]; ?>');">
                    <i class="fas fa-clock"></i>
                  </button>

                <?php } ?>
                <button class="btn btn-outline-info" title="Foto und Einverständniserklärung für diesen Schüler hochladen" onclick="upload('<?php echo $user["id"]; ?>', '<?php echo $user["username"]; ?>')">
                  <i class="fas fa-upload"></i>
                </button>
              </td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
      <hr>
      <div class="container">
        <div class="card p-2 my-3">
          <h6><b>Fotos der Klassenleiter:</b></h6>
          <?php
          $imagePath = "";
          $target_dir = "userdata/" . $_GET["class"] . "/Lehrkraefte/";
          if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
          }
          $safeUsername = getMySafeUsername();
          foreach (array("png", "jpg", "jpeg", "gif") as $extension) {
            $fullpath = $target_dir . $safeUsername  . "." . $extension;
            if (is_file($fullpath)) {
              $imagePath = $fullpath;
            }
          }

          if ($imagePath == "") { ?>
            Bitte laden Sie hier ein Portraitfoto von Ihnen hoch:
            <form method="POST" enctype="multipart/form-data" class="mt-4" action="classes.php?class=<?php echo htmlspecialchars($_GET["class"]); ?>">
              <div class="row">
                <div class="col-md">
                  <b>Portraitfoto:</b>
                </div>
                <div class="col-md-7">
                  <input type="file" class="" name="photo" id="photoInput">
                </div>
              </div>
              <input class="btn btn-outline-success mt-4" type="submit" name="submitTeacherImage" value="Hochladen">
            </form>
            <hr>
          <?php } ?>


          <table class="table table-striped">
            <thead>
              <tr>
                <th>Name</th>
                <th>Portraitfoto</th>
                <th>Aktionen</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($handle = opendir($target_dir)) {
                while (false !== ($entry = readdir($handle))) {
                  if ($entry != "." && $entry != "..") {
                    $username = pathinfo($entry)["filename"];
              ?>
                    <tr>
                      <td><?php echo $username; ?> </td>
                      <td><img class="d-block img-fluid userimg-small cursor-pointer" onclick="openModal('<?php echo $_GET["class"]; ?>', '<?php echo $username; ?>', 'teacher')" src="serveImage.php?type=teacher&class=<?php echo htmlspecialchars($_GET["class"]); ?>&username=<?php echo $username; ?>&thumbnail"></td>
                      <td>
                        <button class="btn btn-outline-danger" title="Portraitfoto löschen" onclick="deleteTeacherPhoto('<?php echo $username; ?>');">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </td>
                    </tr>
              <?php }
                }
                closedir($handle);
              } ?>
            </tbody>
          </table>


        </div>
      </div>
    </div>
    <script>
      function submit(action, userId) {
        const stateElement = event.target.closest("tr").querySelector(".state")
        stateElement.innerHTML = '<span class="text-primary"><i class="fas fa-spinner fa-spin"></i> Laden</span>';
        fetch("classes.php?class=<?php echo $_GET["class"]; ?>", {
          method: "POST",
          body: JSON.stringify({
            action,
            userId,
          }),
          headers: {
            'Content-Type': 'application/json'
          },
        }).then(async (data) => {
          stateElement.innerHTML = await data.text();
        });
      }

      function deleteTeacherPhoto(username) {
        fetch("classes.php?deleteTeacherPhoto&class=<?php echo $_GET["class"]; ?>", {
          method: "POST",
          body: JSON.stringify({
            username,
          }),
          headers: {
            'Content-Type': 'application/json'
          },
        }).then(() => {
          location.reload();
        });
      }
    </script>
  <?php
  } else {
  ?>

    <h1>Klassen:</h1>
    <p>Liebe Klassenleiterin, lieber Klassenleiter,<br>klicken Sie bitte hier auf Ihre Klasse.</p>
    <div class="mt-4">
      <?php foreach ($classes as $jahrgangsstufe) { ?>
        <div class="row mb-2">
          <?php foreach ($jahrgangsstufe as $class) { ?>
            <div class="col">
              <a class="btn btn-block <?php echo (canViewPhotosOfClass($class) ? "btn-outline-dark" : "btn-outline-secondary disabled") ?>" href="classes.php?class=<?php echo $class[0]; ?>">
                <span><?php echo $class[0]; ?></span>
                <span><?php $classSize = intval($class[1]); ?></span>


                <?php

                $percentage_ACCEPTED = count(array_filter($class[4], function ($user) use ($PHOTO_STATES) {
                  return $user["photo_state"] == $PHOTO_STATES["ACCEPTED"];
                })) / $classSize * 100;
                $percentage_UPLOADED = count(array_filter($class[4], function ($user) use ($PHOTO_STATES) {
                  return $user["photo_state"] == $PHOTO_STATES["UPLOADED"];
                })) / $classSize * 100;
                $percentage_REJECTED = count(array_filter($class[4], function ($user) use ($PHOTO_STATES) {
                  return $user["photo_state"] == $PHOTO_STATES["PRIVACY_REJECTED"] || $user["photo_state"] == $PHOTO_STATES["PHOTO_REJECTED"] || $user["photo_state"] == $PHOTO_STATES["BOTH_REJECTED"];
                })) / $classSize * 100;
                $percentage_MISSING = 100 - ($percentage_ACCEPTED + $percentage_REJECTED + $percentage_UPLOADED);

                ?>

                <div class="progress border border-dark">
                  <div class="progress-bar bg-success" style="width: <?php echo $percentage_ACCEPTED; ?>%"></div>
                  <div class="progress-bar bg-danger" style="width: <?php echo $percentage_REJECTED; ?>%"></div>
                  <div class="progress-bar bg-primary" style="width: <?php echo $percentage_UPLOADED; ?>%"></div>
                  <div class="progress-bar bg-light" style="width: <?php echo $percentage_MISSING; ?>%"></div>
                </div>
              </a>
            </div>
          <?php } ?>
          <?php for ($i = 0; $i < 6 - count($jahrgangsstufe); $i++) { ?>
            <div class="col"></div>
          <?php } ?>
        </div>
      <?php } ?>
    </div>
  <?php
  } ?>
</div>

<script>
  function openModal(id, name, type) {
    if (type == "teacher") {
      document.getElementById("modal-title").innerText = `${name} - Klassenleitung`;
      document.getElementById("modal-image").src = `serveImage.php?type=teacher&username=${name}&class=${id}`;
    } else {
      document.getElementById("modal-title").innerText = `${name} - ${type == "photo" ? "Portraitfoto" : "Einverständniserklärung"}`;
      document.getElementById("modal-image").src = `serveImage.php?type=${type}&userId=${id}`;
    }
    $("#imgModal").modal()
  }

  function upload(id, name) {
    document.getElementById("upload-modal-userId").value = id;
    document.getElementById("upload-modal-username").innerText = name;
    $("#uploadModal").modal()
  }
</script>

<div class="modal fade" tabindex="-1" id="imgModal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-title">Loading...</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img class="d-block img-fluid" id="modal-image" src="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Schließen</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" id="uploadModal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-title">Upload für <i id="upload-modal-username"></i></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" enctype="multipart/form-data" action="classes.php?class=<?php echo htmlspecialchars($_GET["class"]); ?>">
          <div class="row">
            <div class="col-md">
              <b>Portraitfoto:</b>
            </div>
            <div class="col-md-7">
              <input type="file" class="" name="photo" id="photoInput">
            </div>
          </div>
          <div class="row">
            <div class="col-md">
              <b>Einverständniserklärung:</b>
            </div>
            <div class="col-md-7">
              <input type="file" class="" name="privacy" id="privacyInput">
            </div>
          </div>
          <input type="hidden" id="upload-modal-userId" name="userId" value="">
          <input class="btn btn-outline-success mt-4" type="submit" name="submit" value="Hochladen">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Schließen</button>
      </div>
    </div>
  </div>
</div>

<?php require_once("partials/foot.php"); ?>