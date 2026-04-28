<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'member.php';

$member = new member();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $message = 'Please fill in all fields.';
    } else {
        $user = $member->getByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['username'];

            header('Location: index.php');
            exit;
        } else {
            $message = 'Invalid email or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link href="Login.css" rel="stylesheet">
</head>

<body>

    <div id="left_color"></div>
    <div id="right_color"></div>

    <div id="header">
        <h1><a href="index.php">Auction-ettes</a></h1>
    </div>

    <div class="auth-container">

        <h1>Login</h1>

        <?php if ($message !== ''): ?>
            <p class="auth-message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST" action="">

            <p>Email
                <input 
                    type="email" 
                    name="email" 
                    size="15" 
                    maxlength="50" 
                    placeholder="Your Email Here"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                />
            </p>

            <p>Password
                <input 
                    type="password" 
                    name="password" 
                    size="15" 
                    maxlength="100" 
                    placeholder="Your Password"
                />
            </p>

            <button type="submit">Login</button>

        </form>

        <p>
            <a href="register.php">Create New Account</a>
        </p>

    </div>

</body>
</html>