<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <div class="container">
        <a href="index.php" class="navbar-brand">AG Klassenfotos 2021</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a href="index.php" class="nav-link <?php onPageActive("index.php") ?>">Home</a>
            </li>
            <?php if (isLoggedin() && ($_SESSION["job"] == "Lehrer" || isAdmin())) { ?>
              <li class="nav-item" data-turbolinks="false">
                <a href="classes.php" class="nav-link <?php onPageActive("classes.php") ?>">Klassen</a>
              </li>
            <?php } ?>
          </ul>
          <ul class="navbar-nav justify-content-end">
            <li class="nav-item">
              <a class="nav-link" href="https://allgaeu-gymnasium.de/impressum" target="_blank">
                Impressum
              </a>
            </li>
            <?php if (isLoggedin()) { ?>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                  aria-haspopup="true" aria-expanded="false">
                    <img src="profileImage.php" class="rounded-circle align-self-center mr-1" style="height: 25px;">
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                  <h5 class="dropdown-item-text mb-0"><?php echo $_SESSION["username"] ?></h5>
                  <p class="dropdown-item-text text-muted mb-0"><?php echo $_SESSION["email"] ?></p>
                  <div class="dropdown-divider"></div>
                  <a href="signout.php" class="dropdown-item">Abmelden</a>
                </div>
              </li>
            <?php } else { ?>
              <li class="nav-item">
                <a href="signin.php" class="nav-link">Sign In</a>
              </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </nav>