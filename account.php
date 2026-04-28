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

$userListings = $listing->getByUser($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Account</title>
    <link href="Homepage.css" rel="stylesheet" />
</head>

<body>

<div id="header">
    <h1><a href="index.php">Auction-ettes</a></h1>

    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> </h2>

    <a href="logout.php">Logout</a>
</div>
<p>
    <a href="delete-account.php">Delete My Account</a>
</p>
<main>

    <h2><a href="create-listing.php">+ Create New Listing</a></h2>

    <h2>Your Listings</h2>

    <?php if (!empty($userListings)): ?>
        <?php foreach ($userListings as $item): ?>

           <div id="item">
    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
    <p><?php echo htmlspecialchars($item['description']); ?></p>
    <p>Starting Price: $<?php echo htmlspecialchars($item['starting_price']); ?></p>
    <p>Current Price: $<?php echo htmlspecialchars($item['current_price']); ?></p>

    <p>
        <a href="edit-listing.php?id=<?php echo urlencode($item['listing_id']); ?>">Edit</a>
        |
        <a href="delete-listing.php?id=<?php echo urlencode($item['listing_id']); ?>"
           onclick="return confirm('Are you sure you want to delete this listing?');">
           Delete
        </a>
    </p>
</div>

        <?php endforeach; ?>
    <?php else: ?>
        <p>You haven't created any listings yet.</p>
    <?php endif; ?>

</main>

</body>
</html>