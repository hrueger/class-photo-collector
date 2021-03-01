<?php require_once("partials/head.php"); ?>
<?php ensureLoggedin() ?>
<?php ensureTeacher() ?>

<?php

$jahrgangsstufen = array(
  array(array("5a", 20), array("5b", 20), array("5c", 20), array("5d", 20),array( "5e", 20)),
  array(array("6a", 20), array("6b", 20), array("6c", 20), array("6d", 20),array( "6e", 20)),
  array(array("7a", 20), array("7b", 20), array("7c", 20), array("7d", 20),array( "7e", 20)),
  array(array("8a", 20), array("8b", 20), array("8c", 20), array("8d", 20),array( "8e", 20)),
  array(array("9a", 20), array("9b", 20), array("9c", 20), array("9d", 20),array( "9e", 20)),
  array(array("10a", 20), array("10b", 20), array("10c", 20), array("10d", 20), array("10e", 20)),
  array(array("11Q", 100), array("12Q", 100))
);

?>

<div class="jumbotron">
<?php if (isset($_GET["class"])) {
  ?>
  <h1>Klasse <?php echo htmlspecialchars($_GET["class"]); ?>:</h1>
  <a class="btn btn-outline-primary" href="classes.php"><i class="fas fa-arrow-back"></i> Zurück</a>
  <?php
} else {
  ?>

  <h1>Klassen:</h1>
  <div class="">
    <?php foreach ($jahrgangsstufen as $jahrgangsstufe) {?>
    <div class="row mb-2">
    <?php foreach ($jahrgangsstufe as $class) {?>
    <div class="col">
    <a class="btn btn-block btn-outline-primary" href="classes.php?class=<?php echo $class[0]; ?>"><?php echo $class[0]; ?></a>
    </div>
    <?php } ?>
    </div>
    <?php } ?>
  </div>
  <?php
}?>
</div>

<?php require_once("partials/foot.php"); ?>