<!DOCTYPE html>
<html lang="en">

<head>
  <title>Bienvenue</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/bootstrap.theme.min.css">
  <!-- <link rel="stylesheet" href="assets/css/custom.css"> -->
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/custom.js"></script>
  <script type="text/javascript">
    /* confirmation de la fonction de suppression  */
    function suppression() {
      var check = confirm('Tu es sûr de vouloir supprimer ça ?');
      if (check) {

        return true;
      } else {
        return false;
      }
    }
  </script>
</head>

<body>

  <nav class="navbar navbar-inverse sidebar navbar-fixed-top" role="navigation">
    <div class="container-fluid">
      <!-- Les boutons sont regroupés pour un meilleur affichage sur les téléphones portables -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>

      <?php
      $user_role = $_SESSION['user_role'];
      if ($user_role == 1) {
      ?>
        <!-- Rassemblez les liens de navigation, les formulaires et autres contenus pour les bascules. -->
        <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-nav-custom">
            <li <?php if ($page_name == "Task_Info") {
                  echo "class=\"active\"";
                } ?>><a href="taches.php">Tâches<span style="font-size:16px; color:;" class="pull-right hidden-xs showopacity glyphicon glyphicon-tasks"></span></a></li>

            <li <?php if ($page_name == "Admin") {
                  echo "class=\"active\"";
                } ?>><a href="gestion-utilisateur.php">Utilisateurs<span style="font-size:16px; color:#d4ab3a;" class="pull-right hidden-xs showopacity glyphicon glyphicon-user"></span></a></li>

            <li><a href="?logout=logout">Déconnexion<span style="font-size:16px; color:#d4ab3a;" class="pull-right hidden-xs showopacity glyphicon glyphicon-log-out"></span></a></li>
          </ul>
        </div>
      <?php
      } else if ($user_role == 2) {

      ?>
        <!-- Rassemblez les liens de navigation, les formulaires et autres contenus pour les bascules. -->
        <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-nav-custom">
            <li <?php if ($page_name == "Task_Info") {
                  echo "class=\"active\"";
                } ?>><a href="taches.php">Tâches<span style="font-size:16px; color:#d4ab3a;" class="pull-right hidden-xs showopacity glyphicon glyphicon-tasks"></span></a></li>
            <li><a href="?logout=logout">Déconnexion<span style="font-size:16px; color:#d4ab3a;" class="pull-right hidden-xs showopacity glyphicon glyphicon-log-out"></span></a></li>
          </ul>
        </div>

      <?php
      } else {
        header('Location: index.php');
      }

      ?>

    </div>
  </nav>

  <div class="main">