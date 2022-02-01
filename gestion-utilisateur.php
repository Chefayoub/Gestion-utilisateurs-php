<?php
require 'authentication.php'; // vérification de l'authentification de l'administrateur 


$utilisateur_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
if ($utilisateur_id == NULL || $security_key == NULL) {
  header('Location: index.php');
}

$user_role = $_SESSION['user_role'];
if ($user_role != 1) {
  header('Location: taches.php');
}

if (isset($_GET['delete_user'])) {
  $action_id = $_GET['admin_id'];

  $task_sql = "DELETE FROM tache WHERE utilisateur_id_t = $action_id";
  $delete_task = $obj_admin->db->prepare($task_sql);
  $delete_task->execute();

  $sql = "DELETE FROM utilisateur WHERE utilisateur_id = :id";
  $sent_po = "gestion-utilisateur.php";
  $obj_admin->suppression_donne($sql, $action_id, $sent_po);
}

$page_name = "Admin";
include("include/menu.php");

if (isset($_POST['add_new_employee'])) {
  $error = $obj_admin->ajout_utilisateur($_POST);
}

?>



<!-- Fenetre pour ajouter un utilisateur -->
<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">

        <button style="float: left !important; color: red !important; opacity:1 !important;
    font-size: 2rem !important;" type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title text-center">Ajout d'un utilisateur</h2>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <?php if (isset($error)) { ?>
              <h5 class="alert alert-danger"><?php echo $error; ?></h5>
            <?php } ?>
            <form role="form" action="" method="post" autocomplete="off">
              <div class="form-horizontal">

                <div class="form-group">
                  <label class="control-label col-sm-2">Identifiant</label>
                  <div class="col-sm-6">
                    <input type="text" name="em_identifiant" class="form-control" required>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-sm-2">Email</label>
                  <div class="col-sm-6">
                    <input type="email" name="em_email" class="form-control" required>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-sm-2">Role</label>
                  <div class="col-sm-6">
                    <input type="select" placeholder="Client ou Travailleur" name="em_statut" list="expense" class="form-control" id="default" required>
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-3">
                    <button type="submit" name="add_new_employee" class="btn btn-success">Valider</button>
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


<!-- Interface pour visualiser les differents utilisateur-->
<div class="row">
  <div class="col-md-12">
    <div class="row">

      <div class="well well-custom">
        <?php if (isset($error)) { ?>
          <script type="text/javascript">
            $('#myModal').modal('show');
          </script>
        <?php } ?>
        <?php if ($user_role == 1) { ?>
          <div style="margin-top: 70px;" class="btn-group">
            <button class="btn btn-success btn-menu" data-toggle="modal" data-target="#myModal">Ajouter un utilisateur</button>
          </div>
        <?php } ?>
        <h2 style="text-align:center;" class="active">Gérer les utilisateurs</h2>
        <div class="gap"></div>
        <div class="table-responsive">
          <table class="table table-codensed table-custom">
            <thead>
              <tr>
                <th>ID</th>
                <th>Role</th>
                <th>Email</th>
                <th>Identifiant</th>
                <th>Mots de passe temporaire</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>

              <?php
              $sql = "SELECT * FROM utilisateur WHERE user_role = 2 ORDER BY utilisateur_id DESC";
              $info = $obj_admin->gerer_donne($sql);
              $serial  = 1;
              $num_row = $info->rowCount();
              if ($num_row == 0) {
                echo '<tr><td colspan="7">No Data found</td></tr>';
              }
              while ($row = $info->fetch(PDO::FETCH_ASSOC)) {
              ?>
                <tr>
                  <td><?php echo $serial;
                      $serial++; ?></td>
                  <td><?php echo $row['statut']; ?></td>
                  <td><?php echo $row['email']; ?></td>
                  <td><?php echo $row['identifiant']; ?></td>
                  <td><?php echo $row['temp_password']; ?></td>

                  <td>
                    <a title="Update Employee" href="modifier-utilisateur.php?admin_id=<?php echo $row['utilisateur_id']; ?>"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                    <a title="Delete" href="?delete_user=delete_user&admin_id=<?php echo $row['utilisateur_id']; ?>" onclick=" return suppression();"><span class="glyphicon glyphicon-trash"></span></a>
                  </td>
                </tr>
              <?php  } ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>