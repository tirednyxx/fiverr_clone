<?php
session_start();
require_once 'classloader.php'; // adjust path if needed

$client_id = $_SESSION['user_id']; // or $_SESSION['client_id']
$proposal_id = $_POST['proposal_id'];
$description = $_POST['description'];

// Step 1: Check if offer already exists
$sql = "SELECT COUNT(*) FROM offers WHERE client_id = ? AND proposal_id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$client_id, $proposal_id]);
$existingOfferCount = $stmt->fetchColumn();

if ($existingOfferCount > 0) {
    $_SESSION['message'] = "You have already sent an offer for this proposal. NOTE: You can only send an offer once per proposal.";
    $_SESSION['status'] = "400";
    header("Location: client_panel.php"); // or wherever the form lives
    exit();
}

// Step 2: Insert new offer
$sql = "INSERT INTO offers (client_id, proposal_id, description, date_sent) VALUES (?, ?, ?, NOW())";
$stmt = $db->prepare($sql);
$stmt->execute([$client_id, $proposal_id, $description]);

$_SESSION['message'] = "Offer submitted successfully!";
$_SESSION['status'] = "200";
header("Location: client_panel.php");
exit();
?>
