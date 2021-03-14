<?php require_once("partials/head.php"); ?>
<?php ensureLoggedin() ?>
<?php // ensureTeacher() 
?>

<?php

$classes = getClasses();

?>

<style>
  .fake-button {
    border-radius: 3px;
  }
</style>

<div class="jumbotron">
  <?php if (isset($_GET["class"])) {
    ensureCanViewPhotosOfClass($_GET["class"]);


    if (isset($_POST["id"])) {
      if (isset($_POST["accept"])) {
        $statement = $db->prepare("UPDATE users SET photo_state = ? WHERE id = ?");
        $statement->execute(array($PHOTO_STATES["ACCEPTED"], $_POST["id"]));
      } else if (isset($_POST["rejectPhoto"])) {
        $statement = $db->prepare("UPDATE users SET photo_state = ? WHERE id = ?");
        $statement->execute(array($PHOTO_STATES["PHOTO_REJECTED"], $_POST["id"]));
      } else if (isset($_POST["rejectPrivacy"])) {
        $statement = $db->prepare("UPDATE users SET photo_state = ? WHERE id = ?");
        $statement->execute(array($PHOTO_STATES["PRIVACY_REJECTED"], $_POST["id"]));
      } else if (isset($_POST["waiting"])) {
        $statement = $db->prepare("UPDATE users SET photo_state = ? WHERE id = ?");
        $statement->execute(array($PHOTO_STATES["UPLOADED"], $_POST["id"]));
      }
    }
  ?>
    <h1>Klasse <?php echo htmlspecialchars($_GET["class"]); ?>:</h1>
    <a class="btn btn-outline-primary" href="classes.php"><i class="fas fa-arrow-back"></i> Zurück</a>
    <div class="card p-2 my-3">
      <b>Anleitung:</b><br>
      <p>Liebe Klassenleiterin, lieber Klassenleiter,<br>bitte führen Sie bei jeder Schülerin / jedem Schüler ihrer Klasse folgende Schritte aus:</p>
      <ol>
        <li>Klicken Sie auf das <b>Portraitfoto</b> und überprüfen Sie die <b>Übereinstimmung von Name und Gesicht</b>.</li>
        <li>Klicken Sie auf die <b>Einverständniserklärung</b> und überprüfen Sie die <b>Vollständigkeit, vor allem die Unterschriften</b>.</li>
        <li>Sind Portraitfoto und Einverständniserklärung <b>in Ordnung</b>, klicken Sie auf <i class="fake-button fas fa-check text-success d-inline-block border border-success p-1"></i>.</li>
        <li>Ist das <b>Portrait nicht in Ordnung</b> (falsche Person, Gesicht nicht / schlecht erkennbar, ...), klicken Sie auf <i class="fake-button fas fa-user-times text-danger d-inline-block border border-danger p-1"></i>.</li>
        <li>Ist die <b>Einverständniserklärung nicht in Ordnung</b> (Unterschriften fehlen, sind nicht glaubhaft, ...), klicken Sie auf <i class="fake-button fas fa-file-alt text-danger d-inline-block border border-danger p-1"></i>.</li>
        <li>Falls Sie Ihre <b>Eingaben rückgängig</b> machen wollen, klicken Sie auf <i class="fake-button fas fa-clock text-primary d-inline-block border border-primary p-1"></i>.</li>
      </ol>
      <div class="alert alert-warning">
        <b>Wichtig!</b>:<br>
        Bitte nehmen Sie im Fall von 4. oder 5. mit dem Schüler über Teams Kontakt auf und erläutern Sie ihm seinen Fehler.<br>
        Bei wirklich unklaren Fällen, schreiben Sie bitte Herrn Herz unter Nennung der Klasse und des Schülernamens über Teams an.
      </div>
      Vielen Dank für Ihre Mitarbeit bei der Erstellung des Jahresberichts!
    </div>
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Name</th>
            <th>Status</th>
            <th>Portraitfoto</th>
            <th>Einverständniserklärung</th>
            <th>Aktionen</th>
          </tr>
        </thead>
        <tbody>
          <?php

          $statement = $db->prepare("SELECT * FROM users WHERE class=?");
          $statement->execute(array($_GET["class"]));
          $users = $statement->fetchAll();

          foreach ($users as $user) {
          ?>
            <tr>
              <td><?php echo $user["username"]; ?></td>
              <td>
                <?php
                if ($user["photo_state"] == $PHOTO_STATES["ACCEPTED"]) {
                  echo "<span class='text-success'>" . $PHOTO_STATES_PRETTY[$user["photo_state"]] . "</span>";
                } else if ($user["photo_state"] == $PHOTO_STATES["UPLOADED"]) {
                  echo "<span class='text-primary'>" . $PHOTO_STATES_PRETTY[$user["photo_state"]] . "</span>";
                } else {
                  echo "<span class='text-danger'>" . $PHOTO_STATES_PRETTY[$user["photo_state"]] . "</span>";
                }

                ?>

              </td>
              <td><?php if ($user["photo_state"] != $PHOTO_STATES["MISSING"]) { ?><img class="d-block img-fluid userimg-small cursor-pointer" onclick="openModal('<?php echo $user['id']; ?>', '<?php echo $user['username']; ?>', 'photo')" src="serveImage.php?type=photo&userId=<?php echo $user["id"]; ?>"><?php } ?></td>
              <td><?php if ($user["photo_state"] != $PHOTO_STATES["MISSING"]) { ?><img class="d-block img-fluid userimg-small cursor-pointer" onclick="openModal('<?php echo $user['id']; ?>', '<?php echo $user['username']; ?>', 'privacy')" src="serveImage.php?type=privacy&userId=<?php echo $user["id"]; ?>"><?php } ?></td>
              <td>
                <form method="POST" class="d-inline">
                  <input type="hidden" name="id" value="<?php echo $user["id"]; ?>">
                  <input type="hidden" name="accept" value="true">
                  <button type="submit" class="btn btn-outline-success" title="Portrait und Einverständniserklärung OK"><i class="fas fa-check"></i></button>
                </form>

                <form method="POST" class="d-inline">
                  <input type="hidden" name="id" value="<?php echo $user["id"]; ?>">
                  <input type="hidden" name="rejectPhoto" value="true">
                  <button type="submit" class="btn btn-outline-danger" title="Portraitfoto nicht in Ordnung"><i class="fas fa-user-times"></i></button>
                </form>

                <form method="POST" class="d-inline">
                  <input type="hidden" name="id" value="<?php echo $user["id"]; ?>">
                  <input type="hidden" name="rejectPrivacy" value="true">
                  <button type="submit" class="btn btn-outline-danger" title="Einverständniserklärung nicht in Ordnung"><i class="fas fa-file-alt"></i></button>
                </form>

                <form method="POST" class="d-inline">
                  <input type="hidden" name="id" value="<?php echo $user["id"]; ?>">
                  <input type="hidden" name="waiting" value="true">
                  <button type="submit" class="btn btn-outline-primary" title="Status zurücksetzen"><i class="fas fa-clock"></i></button>
                </form>
              </td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
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
              <a class="btn btn-block <?php echo (canViewPhotosOfClass($class) ? "btn-outline-primary" : "btn-outline-secondary disabled") ?>" href="classes.php?class=<?php echo $class[0]; ?>"><?php echo $class[0]; ?></a>
            </div>
          <?php } ?>
          <?php for ($i = 0; $i < 6 - count($jahrgangsstufe); $i++) {
          ?> <div class="col"></div> <?php
                                      } ?>
        </div>
      <?php } ?>
    </div>
  <?php
  } ?>
</div>

<script>
  function openModal(id, name, type) {
    document.getElementById("modal-title").innerText = `${name} - ${type == "photo" ? "Portraitfoto" : "Einverständniserklärung"}`;
    document.getElementById("modal-image").src = `serveImage.php?type=${type}&userId=${id}`;
    $("#imgModal").modal()

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


<?php require_once("partials/foot.php"); ?>