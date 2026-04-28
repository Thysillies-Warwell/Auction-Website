<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'listings.php';

$listing = new listing();

$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $items = $listing->search($search);
} else {
    $items = $listing->getFeatured(4); 
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Auction-ettes</title>
    <link rel="stylesheet" href="Homepage.css">
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
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
        <?php else: ?>
            <h2><a href="login.php">Login</a></h2>
            <h2><a href="register.php">Create Account</a></h2>
        <?php endif; ?>

    </div>

    <main>

        <form method="GET" action="index.php">
            <p>
                Search For Items
                <input 
                    type="text"
                    name="search"
                    size="15"
                    maxlength="50"
                    placeholder="Search for items"
                    value="<?php echo htmlspecialchars($search); ?>"
                />
                <button type="submit">Search</button>
            </p>
        </form>

        <?php if (!empty($items)): ?>
            <?php foreach ($items as $item): ?>

                <div id="item">

                    <h1>
                        <a href="item.php?id=<?php echo urlencode($item['listing_id']); ?>">
                            <?php echo htmlspecialchars($item['title']); ?>
                        </a>
                    </h1>

                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                    <p>Starting Price: $<?php echo htmlspecialchars($item['starting_price']); ?></p>
                    <p>Current Price: $<?php echo htmlspecialchars($item['current_price']); ?></p>

                    <?php if (isset($_SESSION['user_id'])): ?>

                        <?php 
                            $userBid = $listing->getUserBid($item['listing_id'], $_SESSION['user_id']); 
                        ?>

                        <?php if ($userBid !== null): ?>
                            <p style="color: green;">
                                You bid: $<?php echo htmlspecialchars($userBid); ?>
                            </p>
                        <?php endif; ?>

                        <form method="POST" action="bid.php">
                            <input 
                                type="hidden" 
                                name="listing_id" 
                                value="<?php echo htmlspecialchars($item['listing_id']); ?>"
                            >

                            <input 
                                type="number" 
                                step="0.01" 
                                name="bid_amount" 
                                placeholder="Enter bid"
                            >

                            <button type="submit">Place Bid</button>
                        </form>

                    <?php else: ?>

                        <p><a href="login.php">Login to place a bid</a></p>

                    <?php endif; ?>

                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <p>No items found.</p>
        <?php endif; ?>

    </main>

</body>
</html>