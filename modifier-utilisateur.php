<?php

require 'authentication.php'; // vérification de l'authentification de l'administrateur 

// auth check
$utilisateur_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
if ($utilisateur_id == NULL || $security_key == NULL) {
  header('Location: index.php');
}

// check admin
$user_role = $_SESSION['user_role'];
if ($user_role != 1) {
  header('Location: taches.php');
}

$admin_id = $_GET['admin_id'];

if (isset($_POST['update_current_employee'])) {

  $obj_admin->miseAjour_utilisateur($_POST, $admin_id);
}

// if (isset($_POST['btn_user_password'])) {

//   $obj_admin->update_user_password($_POST, $admin_id);
// }



$sql = "SELECT * FROM utilisateur WHERE utilisateur_id='$admin_id' ";
$info = $obj_admin->gerer_donne($sql);
$row = $info->fetch(PDO::FETCH_ASSOC);

$page_name = "Admin";
include("include/menu.php");
?>

<div class="row">
  <div class="col-md-12">
    <div class="well well-custom">
      <div class="row">
        <div class="col-md-10 col-md-offset-1">
          <div class="well">
            <h3 class="text-center bg-primary" style="padding: 7px;">Modifier l'utilisateur</h3><br>


            <div class="row">
              <div class="col-md-7">
                <form class="form-horizontal" role="form" action="" method="post" autocomplete="off">
                  <div class="form-group">
                    <label class="control-label col-sm-2">Role</label>
                    <div class="col-sm-8">
                      <input type="text" value="<?php echo $row['statut']; ?>" name="em_statut" list="expense" class="form-control input-custom" id="default" required>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-sm-2">Identifiant</label>
                    <div class="col-sm-8">
                      <input type="text" value="<?php echo $row['identifiant']; ?>" name="em_identifiant" class="form-control input-custom" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-sm-2">Email</label>
                    <div class="col-sm-8">
                      <input type="email" value="<?php echo $row['email']; ?>" name="em_email" class="form-control input-custom" required>
                    </div>
                  </div>

                  <div class="form-group">
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-3">
                      <button type="submit" name="update_current_employee" class="btn btn-success-custom">Valider</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  $('#emlpoyee_pass_btn').click(function() {
    $('#employee_pass_cng').toggle('slow');
  });
</script>