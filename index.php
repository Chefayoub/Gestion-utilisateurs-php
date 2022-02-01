<?php
require 'authentication.php'; // vérification de l'authentification de l'administrateur 

// auth check
if (isset($_SESSION['admin_id'])) {
	$utilisateur_id = $_SESSION['admin_id'];
	$user_name = $_SESSION['admin_name'];
	$security_key = $_SESSION['security_key'];
	if ($utilisateur_id != NULL && $security_key != NULL) {
		header('Location: taches.php');
	}
}

if (isset($_POST['login_btn'])) {
	$info = $obj_admin->verification_co_admin($_POST);
}

$page_name = "Login";
include("include/style-login.php");

?>

<style>
	body {
		background-image: url('./images/work2.jpg');
		background-repeat: no-repeat;
		background-position-x: center;
		background-position-y: -65px;
		overflow-x: hidden;
	}
</style>


<div style="margin-left: 180px; " class="row">
	<div class="col-md-4 col-md-offset-3">

		<div class="well" style="position:relative;top:20vh; background-image:url(./images/vector1);">
			<center>
				<h2 style="margin-top:1px; color:white;">Bienvenue</h2>
			</center>
			<form class="form-horizontal form-custom-login" action="" method="POST">
				<div class="form-heading">
					<h2 class="text-center">Veuillez saisir votre identifiant et mot de passe</h2>
				</div>

				<?php if (isset($info)) { ?>
					<h5 class="alert alert-danger"><?php echo $info; ?></h5>
				<?php } ?>
				<div style="margin-top:10px;" class="form-group">
					<label style="font-size:16px; color:white;" for="">Identifiant</label>
					<input type="text" class="form-control" name="identifiant" required />
				</div>
				<div class="form-group" ng-class="{'has-error': loginForm.password.$invalid && loginForm.password.$dirty, 'has-success': loginForm.password.$valid}">
					<label style=" font-size:16px; color:white;" for="">Mots de passe</label>
					<input type="password" class="form-control" name="admin_password" required />
				</div>
				<button style="font-size:16px;" type="submit" name="login_btn" class="btn btn-info pull-right">Connexion</button>
			</form>
		</div>
	</div>
</div>