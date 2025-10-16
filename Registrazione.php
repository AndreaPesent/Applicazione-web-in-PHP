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
    } elseif (isset($users[$username])) {
        $message = "Account già registrato";
    } else {
        $users[$username] = $password;
        file_put_contents($file, json_encode($users));
        $message = "Account registrato. Ora puoi fare il login";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registrazione</title>
</head>
<body>
<h1>Registrazione</h1>
<form method="post">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Registrati">
</form>
<p>Se sei già registrato: <a href="LoginApp.php"> Fai il login qui</a></p>
</body>
</html>