<?php
session_start();
$file = 'users.json';
$users = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    if ($username === "" || $password === "") 
    {
        $message = "Inserisci username e password.";
    } elseif (!isset($users[$username])) {
        $message = "Utente non registrato. <a href='Registrazione.php'>Registrati qui</a>";
    } elseif ($users[$username] !== $password) 
    { 
        $message = "Password errata.";
    } else {
        $_SESSION['username'] = $username;
        header("Location: Persona.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head><meta charset="UTF-8"><title>Login</title></head>
<body>
<h1>Login</h1>
<?php if ($message): ?><p><b><?= htmlspecialchars($message) ?></b></p><?php endif; ?>
<form method="post">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
</form>
<p>Non sei registrato? <a href="Registrazione.php">Registrati qui</a></p>
</body>
</html>
