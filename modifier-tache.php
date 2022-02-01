<?php

require 'authentication.php'; // // vérification de l'authentification de l'administrateur 

// auth check
$utilisateur_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
if ($utilisateur_id == NULL || $security_key == NULL) {
	header('Location: index.php');
}

// check admin
$user_role = $_SESSION['user_role'];

$tache_id = $_GET['tache_id'];



if (isset($_POST['modification_tache'])) {
	$obj_admin->modification_tache($_POST, $tache_id, $user_role);
}

$page_name = "Edit Task";
include("include/menu.php");

$sql = "SELECT * FROM tache WHERE tache_id='$tache_id' ";
$info = $obj_admin->gerer_donne($sql);
$row = $info->fetch(PDO::FETCH_ASSOC);

?>

<!--modal for employee add-->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<div class="row">
	<div class="col-md-12">
		<div class="well well-custom">
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="well">
						<h3 class="text-center bg-primary" style="padding: 7px;">Edit Task </h3><br>

						<div class="row">
							<div class="col-md-12">
								<form class="form-horizontal" role="form" action="" method="post" autocomplete="off">
									<div class="form-group">
										<label class="control-label col-sm-5">Task Title</label>
										<div class="col-sm-7">
											<input type="text" placeholder="Task Title" id="titre" name="titre" list="expense" class="form-control" value="<?php echo $row['titre']; ?>" <?php if ($user_role != 1) { ?> readonly <?php } ?> val required>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-5">Task Description</label>
										<div class="col-sm-7">
											<textarea name="descriptions" id="descriptions" placeholder="Text Deskcription" class="form-control" rows="5" cols="5"><?php echo $row['descriptions']; ?></textarea>
										</div>
									</div>


									<div class="form-group">
										<label class="control-label col-sm-5">Utilisateur</label>
										<div class="col-sm-7">
											<?php
											$sql = "SELECT utilisateur_id, identifiant FROM utilisateur WHERE user_role = 2";
											$info = $obj_admin->gerer_donne($sql);
											?>
											<select class="form-control" name="assign_to" id="aassign_to" <?php if ($user_role != 1) { ?> disabled="true" <?php } ?>>
												<option value="">Select</option>

												<?php while ($rows = $info->fetch(PDO::FETCH_ASSOC)) { ?>
													<option value="<?php echo $rows['utilisateur_id']; ?>" <?php
																											if ($rows['utilisateur_id'] == $row['utilisateur_id_t']) {
																											?> selected <?php } ?>><?php echo $rows['identifiant']; ?></option>
												<?php } ?>
											</select>
										</div>

									</div>

									<div class="form-group">
										<label class="control-label col-sm-5">Statut</label>
										<div class="col-sm-7">
											<select class="form-control" name="statut" id="statut">
												<option value="0" <?php if ($row['statut'] == 0) { ?>selected <?php } ?>>Incomplète</option>
												<option value="1" <?php if ($row['statut'] == 1) { ?>selected <?php } ?>>En cours</option>
												<option value="2" <?php if ($row['statut'] == 2) { ?>selected <?php } ?>>Terminé</option>
											</select>
										</div>
									</div>

									<div class="form-group">
									</div>
									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-3">

										</div>

										<div class="col-sm-3">
											<button type="submit" name="modification_tache" class="btn btn-success-custom">Valider</button>
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


<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>