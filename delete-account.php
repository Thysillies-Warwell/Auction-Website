<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'member.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$member = new member();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm_delete'])) {
        $success = $member->deleteAccount($_SESSION['user_id']);

        if ($success) {
            session_unset();
            session_destroy();

            header('Location: index.php');
            exit;
        } else {
            $message = 'Account could not be deleted.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Account</title>
    <link href="Homepage.css" rel="stylesheet">
</head>
<body>

<div class="auth-container">
    <h1>Delete Account</h1>

    <p>Are you sure you want to delete your account?</p>
    <p>This cannot be undone.</p>

    <?php if ($message !== ''): ?>
        <p class="auth-message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>
            <input type="checkbox" name="confirm_delete" required>
            Yes, delete my account.
        </label>

        <button type="submit">Delete Account</button>
    </form>

    <p><a href="account.php">Cancel and go back</a></p>
</div>

</body>
</html>