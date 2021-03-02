<?php require_once("partials/head.php"); ?>
<?php ensureLoggedin() ?>
<?php ensureTeacher() ?>

<?php

$classes = getClasses();

?>

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
    <p>Klicke ein Bild an, um es zu vergrößern.</p>
    <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Name</th>
          <th>Status</th>
          <th>Foto</th>
          <th>Datenschutzerklärung</th>
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
            <td><?php echo $PHOTO_STATES_PRETTY[$user["photo_state"]]; ?></td>
            <td><?php if ($user["photo_state"] != $PHOTO_STATES["MISSING"]) { ?><img class="d-block img-fluid userimg-small cursor-pointer" onclick="openModal('<?php echo $user['id']; ?>', '<?php echo $user['username']; ?>', 'photo')" src="serveImage.php?type=photo&userId=<?php echo $user["id"]; ?>"><?php } ?></td>
            <td><?php if ($user["photo_state"] != $PHOTO_STATES["MISSING"]) { ?><img class="d-block img-fluid userimg-small cursor-pointer" onclick="openModal('<?php echo $user['id']; ?>', '<?php echo $user['username']; ?>', 'privacy')" src="serveImage.php?type=privacy&userId=<?php echo $user["id"]; ?>"><?php } ?></td>
            <td>
              <form method="POST" class="d-inline">
                <input type="hidden" name="id" value="<?php echo $user["id"]; ?>">
                <input type="hidden" name="accept" value="true">
                <button type="submit" class="btn btn-outline-success"><i class="fas fa-check"></i></button>
              </form>
              
              <form method="POST" class="d-inline">
                <input type="hidden" name="id" value="<?php echo $user["id"]; ?>">
                <input type="hidden" name="waiting" value="true">
                <button type="submit" class="btn btn-outline-primary"><i class="fas fa-clock"></i></button>
              </form>

              <form method="POST" class="d-inline">
                <input type="hidden" name="id" value="<?php echo $user["id"]; ?>">
                <input type="hidden" name="rejectPhoto" value="true">
                <button type="submit" class="btn btn-outline-danger"><i class="fas fa-user-times"></i></button>
              </form>
              
              <form method="POST" class="d-inline">
                <input type="hidden" name="id" value="<?php echo $user["id"]; ?>">
                <input type="hidden" name="rejectPrivacy" value="true">
                <button type="submit" class="btn btn-outline-danger"><i class="fas fa-file-alt"></i></button>
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
    <div class="">
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
    document.getElementById("modal-title").innerText = `${name} - ${type == "photo" ? "Foto" : "Datenschutzerklärung"}`;
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