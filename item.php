<?php
session_start();
require_once 'listings.php';

$listing = new listing();

$listingId = (int)($_GET['id'] ?? 0);

$item = $listing->getById($listingId);

if (!$item) {
    die("Item not found.");
}
?>



<!DOCTYPE html>
<html>
<head>
  <title><?php echo htmlspecialchars($item['title']); ?></title>
  <link href="item.css" rel="stylesheet" />
</head>

<body>

<div id="header">

    <h1><a href="index.php">Auction-ettes</a></h1>

    <button class="advert">
        Find a New Item!
        <h3>?</h3>
        Click Me To Start Exploring!
    </button>

    <?php if (isset($_SESSION['user_id'])): ?>
        <h2><a href="account.php">Your Account</a></h2>
        <h2><a href="logout.php">Logout</a></h2>
    <?php else: ?>
        <h2><a href="login.php">Login</a></h2>
    <?php endif; ?>

</div>

<main>

    <div id="item_name">
        <h1><?php echo htmlspecialchars($item['title']); ?></h1>

        <div id="item_bid">
            <p>Place Bid (must be higher than current bid)</p>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="POST" action="bid.php">
                    <input type="hidden" name="listing_id" value="<?php echo $item['listing_id']; ?>">
                    <input type="number" step="0.01" name="bid_amount" placeholder="Enter Bid"/>
                    <button type="submit">Bid 💰</button>
                </form>
            <?php else: ?>
                <p><a href="login.php">Login to bid</a></p>
            <?php endif; ?>
        </div>
    </div>

    <div id="left_half">

        <div id="item_description">
            <p><?php echo htmlspecialchars($item['description']); ?></p>
        </div>

        <div id="right_half">

            <div id="item_image">
                <p>Image coming soon </p>
            </div>

            <div id="item_properties">
                <p>Owner ID: <?php echo htmlspecialchars($item['user_id']); ?></p>
                <p>Starting Price: $<?php echo htmlspecialchars($item['starting_price']); ?></p>
                <p>Current Price: $<?php echo htmlspecialchars($item['current_price']); ?></p>
                <p>Ends At: <?php echo htmlspecialchars($item['ends_at']); ?></p>
            </div>

        </div>

    </div>

</main>

</body>
</html>