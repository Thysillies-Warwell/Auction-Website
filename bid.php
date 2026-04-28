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

$listingId = (int)($_POST['listing_id'] ?? 0);
$bidAmount = (float)($_POST['bid_amount'] ?? 0);

if ($listingId > 0 && $bidAmount > 0) {
    $listing->placeBid($listingId, $_SESSION['user_id'], $bidAmount);
}

header('Location: index.php');
exit;