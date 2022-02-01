<?php

require 'authentication.php'; // vérification de l'authentification de l'administrateur 


$utilisateur_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
if ($utilisateur_id == NULL || $security_key == NULL) {
  header('Location: index.php');
}


$user_role = $_SESSION['user_role'];


if (isset($_GET['delete_task'])) {
  $action_id = $_GET['tache_id'];

  $sql = "DELETE FROM tache WHERE tache_id = :id";
  $sent_po = "taches.php";
  $obj_admin->suppression_donne($sql, $action_id, $sent_po);
}

if (isset($_POST['add_task_post'])) {
  $obj_admin->ajout_tache($_POST);
}

$page_name = "Task_Info";
include("include/menu.php");

?>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Message bonjour  -->
<div style="margin-top: 80px; margin-bottom:25px;" class="col-md-12 text-center">
  <span style="color: green;"> Bonjour <strong><?php echo $user_name; ?></strong>, Voici les differentes tâches !</span>
</div>

<!-- Ajouter une tache  -->
<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog add-category-modal">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button style="float: left !important; color: red !important; opacity:1 !important;
    font-size: 2rem !important;" type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title text-center">Encoder les informations de la tâche</h2>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <form role="form" action="" method="post" autocomplete="off">
              <div class="form-horizontal">
                <div class="form-group">
                  <label class="control-label col-sm-2">Titre</label>
                  <div class="col-sm-7">
                    <input type="text" id="titre" name="titre" list="expense" class="form-control" id="default" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-2">Description</label>
                  <div class="col-sm-7">
                    <textarea name="descriptions" id="descriptions" class="form-control" rows="5" cols="5"></textarea>
                  </div>
                </div>


                <div class="form-group">
                  <label class="control-label col-sm-2">Utilisateur</label>
                  <div class="col-sm-7">
                    <?php
                    $sql = "SELECT utilisateur_id, identifiant FROM utilisateur WHERE user_role = 2";
                    $info = $obj_admin->gerer_donne($sql);
                    ?>
                    <select class="form-control" name="assign_to" id="aassign_to" required>

                      <?php while ($row = $info->fetch(PDO::FETCH_ASSOC)) { ?>
                        <option value="<?php echo $row['utilisateur_id']; ?>"><?php echo $row['identifiant']; ?></option>
                      <?php } ?>
                    </select>
                  </div>

                </div>
                <div class="form-group">
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-3">
                    <button type="submit" name="add_task_post" class="btn btn-success">Valider</button>
                  </div>
                  <div class="col-sm-3">
                    <button type="submit" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>



<!-- Interface des differentes taches -->
<div class="row">
  <div class="col-md-12">
    <div class="well well-custom">
      <div class="gap"></div>
      <div class="row">
        <div class="col-md-8">
          <div class="btn-group">
            <?php if ($user_role == 1) { ?>
              <div class="btn-group">
                <button class="btn btn-warning btn-menu" data-toggle="modal" data-target="#myModal">Nouvelle tâche</button>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>

      <h2 style="text-align:center;">Gestion des tâches</h2>
      <div class="gap"></div>

      <div class="table-responsive">
        <table class="table table-codensed table-custom">
          <thead>
            <tr>
              <th>ID</th>
              <th>Titre</th>
              <th>Description</th>
              <th>Utilisateur</th>
              <th>Statut</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>

            <?php
            if ($user_role == 1) {
              $sql = "SELECT a.*, b.identifiant 
                        FROM tache a
                        INNER JOIN utilisateur b ON(a.utilisateur_id_t = b.utilisateur_id)
                        ORDER BY a.tache_id DESC";
            } else {
              $sql = "SELECT a.*, b.identifiant 
                  FROM tache a
                  INNER JOIN utilisateur b ON(a.utilisateur_id_t = b.utilisateur_id)
                  WHERE a.utilisateur_id_t = $utilisateur_id
                  ORDER BY a.tache_id DESC";
            }

            $info = $obj_admin->gerer_donne($sql);
            $serial  = 1;
            $num_row = $info->rowCount();
            if ($num_row == 0) {
              echo '<tr><td colspan="7">Pas de tâches</td></tr>';
            }
            while ($row = $info->fetch(PDO::FETCH_ASSOC)) {
            ?>
              <tr>
                <td><?php echo $serial;
                    $serial++; ?></td>
                <td><?php echo $row['titre']; ?></td>
                <td><?php echo $row['descriptions']; ?></td>
                <td><?php echo $row['identifiant']; ?></td>
                <td>
                  <?php if ($row['statut'] == 1) {
                    echo "En cours <span style='color:#d4ab3a;' class=' glyphicon glyphicon-refresh' >";
                  } elseif ($row['statut'] == 2) {
                    echo "Terminé <span style='color:#00af16;' class=' glyphicon glyphicon-ok' >";
                  } else {
                    echo "Incomplète <span style='color:#d00909;' class=' glyphicon glyphicon-remove' >";
                  } ?>
                </td>

                <td><a title="Update Task" href="modifier-tache.php?tache_id=<?php echo $row['tache_id']; ?>"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                  <?php if ($user_role == 1) { ?>
                    <a title="Delete" href="?delete_task=delete_task&tache_id=<?php echo $row['tache_id']; ?>" onclick=" return suppression();"><span class="glyphicon glyphicon-trash"></span></a>
                </td>
              <?php } ?>
              </tr>
            <?php } ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>