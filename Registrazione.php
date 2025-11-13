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
    } elseif (isset($users[$username])) 
    {
        $message = "Questo username è già registrato.";
    } else 
    {
        $users[$username] = $password;
        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
        $message = "Registrazione completata! Ora puoi fare il login.";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head><meta charset="UTF-8"><title>Registrazione</title></head>
<body>
<h1>Registrazione</h1>
<?php if ($message): ?><p><b><?= htmlspecialchars($message) ?></b></p><?php endif; ?>
<form method="post">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Registrati">
</form>
<p>Se sei già registrato, <a href="LoginApp.php">fai il login qui</a>.</p>
</body>
</html>
