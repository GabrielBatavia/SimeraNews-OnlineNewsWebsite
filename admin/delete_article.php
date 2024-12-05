<?php
// delete_article.php

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require '../includes/db.php'; // MongoDB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $articleId = $_POST['id'];

        try {
            $result = $newsCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($articleId)]);
            if ($result->getDeletedCount() === 1) {
                $_SESSION['message'] = "Artikel berhasil dihapus.";
            } else {
                $_SESSION['message'] = "Artikel tidak ditemukan.";
            }
        } catch (Exception $e) {
            $_SESSION['message'] = "Terjadi kesalahan: " . $e->getMessage();
        }
    } else {
        $_SESSION['message'] = "ID artikel tidak diberikan.";
    }
}

header('Location: admin_dashboard.php');
exit;
?>
