
<?php
    /* Your password */
    $password = 'yourpassword';

    /* Redirects here after login */
    $redirect_after_login = 'entry.php';

    /* Will not ask password again for */
    $remember_password = strtotime('+10 minutes'); // 1 hour

    if (isset($_POST['password']) && $_POST['password'] == $password) {
        setcookie("password", $password, $remember_password);
        header('Location: ' . $redirect_after_login);
        exit;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <div style="text-align:center;margin-top:50px; margin-bottom:20px; font-family:sans-serif;">
        You must enter the password to view this content.
        <form method="POST">
            <input type="password" name="password">
        </form>
    </div>
</body>
</html>
