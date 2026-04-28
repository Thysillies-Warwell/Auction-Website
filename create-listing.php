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
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $startingPrice = trim($_POST['starting_price'] ?? '');

    if ($title === '' || $description === '' || $startingPrice === '') {
        $message = 'Please fill in all fields.';
    } elseif (!is_numeric($startingPrice)) {
        $message = 'Starting price must be a number.';
    } else {
        $success = $listing->create(
            $_SESSION['user_id'],
            $title,
            $description,
            (float)$startingPrice
        );

        if ($success) {
            header('Location: index.php');
            exit;
        } else {
            $message = 'Listing could not be created.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Listing</title>
            <link rel="stylesheet" href="Homepage.css">

</head>
<body>

<div class="create-container">

<h1>Create Listing</h1>

<?php if ($message !== ''): ?>
    <p class="create-message"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="POST">

    <label>Title:</label>
    <input type="text" name="title">

    <label>Description:</label>
    <textarea name="description"></textarea>

    <label>Starting Price:</label>
    <input type="number" step="0.01" name="starting_price">

    <button type="submit">Create Listing</button>

</form>

</div>

</body>
</html>