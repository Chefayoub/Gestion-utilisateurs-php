<?php
require 'authentication.php'; //// vérification de l'authentification de l'administrateur 

// auth check
if (isset($_SESSION['admin_id'])) {
	$utilisateur_id = $_SESSION['admin_id'];
	$user_name = $_SESSION['name'];
	$security_key = $_SESSION['security_key'];
}

if (isset($_POST['change_password_btn'])) {
	$info = $obj_admin->modification_mdp_utilisateur($_POST);
}

$page_name = "Login";
include("include/style-login.php");

?>

<style>
	body {

		background-image: url('./images/password.jpg');
		background-repeat: no-repeat;
		background-position-x: 950px;
		background-position-y: 150px;
		overflow-x: hidden;
	}
</style>

<div class="row">
	<div class="col-md-4 col-md-offset-3">

		<div class="well" style="position:relative;top:20vh; background-image: url('./images/newpassword.jpg');">
			<form class="form-horizontal form-custom-login" action="" method="POST">
				<div class="form-heading" style="background: red;">
					<h2 class="text-center">Veuillez changer votre mots de passe</h2>
				</div>
				<!-- <div class="login-gap"></div> -->
				<?php if (isset($info)) { ?>
					<h5 class="alert alert-danger"><?php echo $info; ?></h5>
				<?php } ?>

				<div class="form-group">
					<label style="font-size:16px; color:white;">Nouveau mots de passe</label>
					<input type="hidden" class="form-control" name="utilisateur_id" value="<?php echo $utilisateur_id; ?>" required />
					<input type="password" class="form-control" name="password" required />
				</div>
				<div class="form-group">
					<label style=" font-size:16px; color:white;">Confirmer mots de passe</label>
					<input type="password" class="form-control" name="re_password" required />
				</div>
				<button style="font-size:16px;" type="submit" name="change_password_btn" class="btn btn-info pull-right">Valider</button>
			</form>
		</div>
	</div>
</div>