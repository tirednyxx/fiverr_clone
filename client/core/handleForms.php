<?php  
require_once '../classloader.php';
$db = $databaseObj->getPDO();

if (isset($_POST['insertNewUserBtn'])) {
	$username = htmlspecialchars(trim($_POST['username']));
	$email = htmlspecialchars(trim($_POST['email']));
	$contact_number = htmlspecialchars(trim($_POST['contact_number']));
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {
		if ($password == $confirm_password) {
			if (!$userObj->usernameExists($username)) {
				if ($userObj->registerUser($username, $email, $password, $contact_number)) {
					header("Location: ../login.php");
				} else {
					$_SESSION['message'] = "An error occurred with the query!";
					$_SESSION['status'] = '400';
					header("Location: ../register.php");
				}
			} else {
				$_SESSION['message'] = $username . " as username is already taken";
				$_SESSION['status'] = '400';
				header("Location: ../register.php");
			}
		} else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
		}
	} else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	if (!empty($email) && !empty($password)) {
		if ($userObj->loginUser($email, $password)) {
			header("Location: ../index.php");
		} else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
		}
	} else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../login.php");
	}
}

if (isset($_GET['logoutUserBtn'])) {
	$userObj->logout();
	header("Location: ../index.php");
}

if (isset($_POST['updateUserBtn'])) {
	$contact_number = htmlspecialchars($_POST['contact_number']);
	$bio_description = htmlspecialchars($_POST['bio_description']);
	if ($userObj->updateUser($contact_number, $bio_description, $_SESSION['user_id'])) {
		header("Location: ../profile.php");
	}
}

if (isset($_POST['insertOfferBtn'])) {
	$user_id = $_SESSION['user_id'];
	$proposal_id = $_POST['proposal_id'];
	$description = htmlspecialchars($_POST['description']);

	// ✅ Check for duplicate offer
	$sql = "SELECT COUNT(*) FROM offers WHERE user_id = ? AND proposal_id = ?";
	$stmt = $db->prepare($sql);
	$stmt->execute([$user_id, $proposal_id]);
	$existingOfferCount = $stmt->fetchColumn();

	if ($existingOfferCount > 0) {
		$_SESSION['message'] = "You have already sent an offer for this proposal. NOTE: You can only send an offer once per proposal.";
		$_SESSION['status'] = "400";
		header("Location: ../index.php");
		exit();
	}

	// ✅ Insert new offer
	if ($offerObj->createOffer($user_id, $description, $proposal_id)) {
		$_SESSION['message'] = "Offer submitted successfully!";
		$_SESSION['status'] = "200";
		header("Location: ../index.php");
		exit();
	} else {
		$_SESSION['message'] = "Failed to submit offer.";
		$_SESSION['status'] = "500";
		header("Location: ../index.php");
		exit();
	}
}

if (isset($_POST['updateOfferBtn'])) {
	$description = htmlspecialchars($_POST['description']);
	$offer_id = $_POST['offer_id'];
	if ($offerObj->updateOffer($description, $offer_id)) {
		$_SESSION['message'] = "Offer updated successfully!";
		$_SESSION['status'] = '200';
		header("Location: ../index.php");
	}
}

if (isset($_POST['deleteOfferBtn'])) {
	$offer_id = $_POST['offer_id'];
	if ($offerObj->deleteOffer($offer_id)) {
		$_SESSION['message'] = "Offer deleted successfully!";
		$_SESSION['status'] = '200';
		header("Location: ../index.php");
	}
}
