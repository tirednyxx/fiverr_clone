<?php
session_start();
require_once 'classloader.php';
 // Adjust path if needed

// Check if user is logged in and is a freelancer
if (!$userObj->isLoggedIn() || $userObj->isAdmin()) {
    header("Location: ../client/login.php");
    exit();
}

// Validate form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insertNewProposalBtn'])) {
    $description = $_POST['description'] ?? '';
    $min_price = $_POST['min_price'] ?? '';
    $max_price = $_POST['max_price'] ?? '';
    $user_id = $_SESSION['user_id'];

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $targetFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
            // Save proposal to database
            $insertSuccess = $proposalObj->insertProposal([
                'user_id' => $user_id,
                'description' => $description,
                'min_price' => $min_price,
                'max_price' => $max_price,
                'image' => $fileName
            ]);

            if ($insertSuccess) {
                $_SESSION['message'] = "Proposal submitted successfully!";
                $_SESSION['status'] = "200";
            } else {
                $_SESSION['message'] = "Failed to save proposal to database.";
                $_SESSION['status'] = "500";
            }
        } else {
            $_SESSION['message'] = "Failed to move uploaded file.";
            $_SESSION['status'] = "500";
        }
    } else {
        $_SESSION['message'] = "No image uploaded or upload error.";
        $_SESSION['status'] = "400";
    }

    header("Location: index.php");
    exit();
} else {
    $_SESSION['message'] = "Invalid form submission.";
    $_SESSION['status'] = "400";
    header("Location: index.php");
    exit();
}
