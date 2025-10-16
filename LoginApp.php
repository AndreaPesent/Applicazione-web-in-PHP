<?php
    session_start();
    $file = 'users.json';
    if (file_exists($file)) {
    $users = json_decode(file_get_contents($file), true);
    if (!$users) {
        $users = array();
    }
} else {
    $users = array();
}
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username == "" || $password == "") {
        $message = "Inserisci username e password";
    } elseif (!isset($users[$username])) {
        $message = "Utente non registrato, <a href='register.php'>registrati qui</a>";
    } elseif ($users[$username] != $password) {
        $message = "Password sbagliata.";
    } else {
        $_SESSION['username'] = $username;
        echo "Accesso effettuato come ". $_SESSION['username'];
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h1>Login</h1>
<form method="post">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
</form>
<p>Non sei registrato? <a href="Registrazione.php">Registrati qui</a></p>
</body>
</html>