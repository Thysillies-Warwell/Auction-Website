<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'member.php';

$member = new member();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' ||  $email === '' || $password === '') {
        $message = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email.';
    } elseif ($member->emailExists($email)) {
        $message = 'That email is already registered.';
    } else {
        $success = $member->create($username, $email, $password);

        if ($success) {
            $user = $member->getByEmail($email);

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['username'];

            header('Location: index.php');
            exit;
        } else {
            $message = 'Account could not be created.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
        <link href="register.css" rel="stylesheet">

</head>
<body>

<div id="header">
    <h1><a href="index.php">Auction-ettes</a></h1>
</div>

    <div class="auth-container">

<h1>Create Account</h1>

<?php if ($message !== ''): ?>
    <p class="auth-message"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="POST">
    <div>
    <label>Username:</label>
    <input type="text" name="username">

    </div>

    <div>
        <label>Email:</label>
        <input type="email" name="email">    
    </div>

    <div>
        <label>Password:</label>
        <input type="password" name="password">
    </div>

    <button type="submit">Create Account</button>
</form>

<p><a href="login.php">Already have an account? Login</a></p>

</div>
</body>
</html>