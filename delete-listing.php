<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'listings.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$listing = new listing();

$listingId = (int)($_GET['id'] ?? 0);

if ($listingId > 0) {
    $listing->delete($listingId, $_SESSION['user_id']);
}

header('Location: account.php');
exit;