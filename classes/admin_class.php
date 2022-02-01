<?php

class Admin_Class
{

	/* -------------------------Connexion a la base de données grace a PDO---------------------- */

	public function __construct()
	{
		$host_name = 'localhost';
		$user_name = 'root';
		$password = '';
		$db_name = 'bd-gestion-utilisateur';

		try {
			$connection = new PDO("mysql:host={$host_name}; dbname={$db_name}", $user_name,  $password);
			$this->db = $connection; // connexion établie
		} catch (PDOException $message) {
			echo $message->getMessage();
		}
	}

	/* ---------------------- Methode pour recuperer les données qui sont rentrer par l'utilisateur ----------------------------------- */

	public function donne_entre($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}


	/* ---------------------- On va verifier que la connexion est faite par l'administrateur ----------------------------------- */

	public function verification_co_admin($data)
	{

		$upass = $this->donne_entre(md5($data['admin_password']));
		$identifiant = $this->donne_entre($data['identifiant']);
		try {
			$stmt = $this->db->prepare("SELECT * FROM utilisateur WHERE identifiant=:uname AND password=:upass LIMIT 1");
			$stmt->execute(array(':uname' => $identifiant, ':upass' => $upass));
			$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($stmt->rowCount() > 0) {
				session_start();
				$_SESSION['admin_id'] = $userRow['utilisateur_id'];
				$_SESSION['name'] = $userRow['statut'];
				$_SESSION['security_key'] = 'rewsgf@%^&*nmghjjkh';
				$_SESSION['user_role'] = $userRow['user_role'];
				$_SESSION['temp_password'] = $userRow['temp_password'];

				if ($userRow['temp_password'] == null) {
					header('Location: taches.php');
				} else {
					header('Location: modifier-mdp-utilisateur.php');
				}
			} else {
				$message = 'Nom d\'utilisateur ou mot de passe invalide';
				return $message;
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	/* ---------------------- Modification du mots de passe temporaire par le nouveau ----------------------------------- */

	public function modification_mdp_utilisateur($data)
	{
		$password  = $this->donne_entre($data['password']);
		$re_password = $this->donne_entre($data['re_password']);

		$utilisateur_id = $this->donne_entre($data['utilisateur_id']);
		$final_password = md5($password);
		$temp_password = '';

		if ($password == $re_password) {
			try {
				$update_user = $this->db->prepare("UPDATE utilisateur SET password = :x, temp_password = :y WHERE utilisateur_id = :id ");

				$update_user->bindparam(':x', $final_password);
				$update_user->bindparam(':y', $temp_password);
				$update_user->bindparam(':id', $utilisateur_id);
				$update_user->execute();



				$stmt = $this->db->prepare("SELECT * FROM utilisateur WHERE utilisateur_id=:id LIMIT 1");
				$stmt->execute(array(':id' => $utilisateur_id));
				$userRow = $stmt->fetch(PDO::FETCH_ASSOC);

				if ($stmt->rowCount() > 0) {
					session_start();
					$_SESSION['admin_id'] = $userRow['utilisateur_id'];
					$_SESSION['name'] = $userRow['statut'];
					$_SESSION['security_key'] = 'rewsgf@%^&*nmghjjkh';
					$_SESSION['user_role'] = $userRow['user_role'];
					$_SESSION['temp_password'] = $userRow['temp_password'];

					header('Location: taches.php');
				}
			} catch (PDOException $e) {
				echo $e->getMessage();
			}
		} else {
			$message = 'Sorry !! Password Can not match';
			return $message;
		}
	}


	/* -------------------- Deconnection de l'administrateur ----------------------------------- */

	public function deconnection_admin()
	{

		session_start();
		unset($_SESSION['admin_id']);
		unset($_SESSION['admin_name']);
		unset($_SESSION['security_key']);
		unset($_SESSION['user_role']);
		header('Location: index.php');
	}

	/*----------- L'ajout d'un utilisateur : On lui genere un code aleatoirement grace a la methode md5 de cryptage--------------*/

	public function ajout_utilisateur($data)
	{
		$user_statut  = $this->donne_entre($data['em_statut']);
		$user_identifiant = $this->donne_entre($data['em_identifiant']);
		$user_email = $this->donne_entre($data['em_email']);
		$temp_password = rand(000000001, 10000000);
		$user_password = $this->donne_entre(md5($temp_password));
		$user_role = 2;
		try {
			$sqlEmail = "SELECT email FROM utilisateur WHERE email = '$user_email' ";
			$query_result_for_email = $this->gerer_donne($sqlEmail);
			$total_email = $query_result_for_email->rowCount();

			$sqlidentifiant = "SELECT identifiant FROM utilisateur WHERE identifiant = '$user_identifiant' ";
			$query_result_for_identifiant = $this->gerer_donne($sqlidentifiant);
			$total_identifiant = $query_result_for_identifiant->rowCount();

			if ($total_email != 0 && $total_identifiant != 0) {
				$message = "Email and Password both are already taken";
				return $message;
			} elseif ($total_identifiant != 0) {
				$message = "identifiant Already Taken";
				return $message;
			} elseif ($total_email != 0) {
				$message = "Email Already Taken";
				return $message;
			} else {
				$add_user = $this->db->prepare("INSERT INTO utilisateur (statut, identifiant, email, password, temp_password, user_role) VALUES (:x, :y, :z, :a, :b, :c) ");

				$add_user->bindparam(':x', $user_statut);
				$add_user->bindparam(':y', $user_identifiant);
				$add_user->bindparam(':z', $user_email);
				$add_user->bindparam(':a', $user_password);
				$add_user->bindparam(':b', $temp_password);
				$add_user->bindparam(':c', $user_role);

				$add_user->execute();
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}


	/* --------- Mise a jour utilisateur ----------*/

	public function miseAjour_utilisateur($data, $id)
	{
		$user_statut  = $this->donne_entre($data['em_statut']);
		$user_identifiant = $this->donne_entre($data['em_identifiant']);
		$user_email = $this->donne_entre($data['em_email']);
		try {
			$update_user = $this->db->prepare("UPDATE utilisateur SET statut = :x, identifiant = :y, email = :z WHERE utilisateur_id = :id ");

			$update_user->bindparam(':x', $user_statut);
			$update_user->bindparam(':y', $user_identifiant);
			$update_user->bindparam(':z', $user_email);
			$update_user->bindparam(':id', $id);

			$update_user->execute();

			$_SESSION['update_user'] = 'update_user';

			header('Location: gestion-utilisateur.php');
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}


	/* ------------Mise a jour admin-------------------- */

	public function miseAjour_admin($data, $id)
	{
		$user_statut  = $this->donne_entre($data['em_statut']);
		$user_identifiant = $this->donne_entre($data['em_identifiant']);
		$user_email = $this->donne_entre($data['em_email']);

		try {
			$update_user = $this->db->prepare("UPDATE utilisateur SET statut = :x, identifiant = :y, email = :z WHERE utilisateur_id = :id ");

			$update_user->bindparam(':x', $user_statut);
			$update_user->bindparam(':y', $user_identifiant);
			$update_user->bindparam(':z', $user_email);
			$update_user->bindparam(':id', $id);

			$update_user->execute();

			header('Location: manage-admin.php');
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	// /* ------------- Recupere les differentes données qui concerne une tache lors de l'encodage------------*/

	public function ajout_tache($data)
	{
		// Données
		$titre  = $this->donne_entre($data['titre']);
		$descriptions = $this->donne_entre($data['descriptions']);
		$assign_to = $this->donne_entre($data['assign_to']);

		try {
			$add_task = $this->db->prepare("INSERT INTO tache (titre, descriptions, utilisateur_id_t) VALUES (:x, :y, :z) ");

			$add_task->bindparam(':x', $titre);
			$add_task->bindparam(':y', $descriptions);
			$add_task->bindparam(':z', $assign_to);

			$add_task->execute();

			$_SESSION['Task_msg'] = 'Ajout de tâches avec succès';

			header('Location: taches.php');
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	// /* ------------- Modification d'une tache ------------*/

	public function modification_tache($data, $tache_id, $user_role)
	{
		$titre  = $this->donne_entre($data['titre']);
		$descriptions = $this->donne_entre($data['descriptions']);
		// $t_start_time = $this->donne_entre($data['t_start_time']);
		// $t_end_time = $this->donne_entre($data['t_end_time']);
		$statut = $this->donne_entre($data['statut']);

		if ($user_role == 1) {
			$assign_to = $this->donne_entre($data['assign_to']);
		} else {
			$sql = "SELECT * FROM tache WHERE tache_id='$tache_id' ";
			$info = $this->gerer_donne($sql);
			$row = $info->fetch(PDO::FETCH_ASSOC);
			$assign_to = $row['utilisateur_id_t'];
		}

		try {
			$update_task = $this->db->prepare("UPDATE tache SET titre = :x, descriptions = :y, utilisateur_id_t = :z, statut = :a WHERE tache_id = :id ");

			$update_task->bindparam(':x', $titre);
			$update_task->bindparam(':y', $descriptions);
			$update_task->bindparam(':z', $assign_to);
			$update_task->bindparam(':a', $statut);
			$update_task->bindparam(':id', $tache_id);

			$update_task->execute();

			$_SESSION['Task_msg'] = 'Mise à jour de la tâche réussie';

			header('Location: taches.php');
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}


	/* -------------------- Lorsqu'on veut supprimer une quelconque données --------------*/

	public function suppression_donne($sql, $action_id, $sent_po)
	{
		try {
			$delete_data = $this->db->prepare($sql);

			$delete_data->bindparam(':id', $action_id);

			$delete_data->execute();

			header('Location: ' . $sent_po);
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	/* ----------------------permet de gerer la recuperation de données --------------------- */

	public function gerer_donne($sql)
	{
		try {
			$info = $this->db->prepare($sql);
			$info->execute();
			return $info;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
}
