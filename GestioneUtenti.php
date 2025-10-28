<?php
session_start();
if (!isset($_SESSION['username'])) 
{
    header("Location: LoginApp.php");
    exit();
}
$file = 'users.json';
$users = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$msg = "";
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: LoginApp.php");
    exit();
}
if (isset($_GET['del'])) 
{
    $del = $_GET['del'];
    if (isset($users[$del])) {
        unset($users[$del]);
        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
        $msg = "Utente eliminato.";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $old = $_POST['old_username'];
    $new = trim($_POST['new_username']);
    $newPass = trim($_POST['new_password']);
    if ($new === "") 
    {
        $msg = "Username non valido.";
    } elseif ($old !== $new && isset($users[$new])) 
    {
        $msg = "Username già esistente.";
    } else {
        if ($old !== $new) unset($users[$old]);
        if ($newPass !== "") 
        {
            $users[$new] = password_hash($newPass, PASSWORD_DEFAULT);
        } else {
            $users[$new] = $users[$old];
        }
        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
        $msg = "Utente aggiornato.";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head><meta charset="UTF-8"><title>Gestione Utenti</title></head>
<body>
<h1>Gestione Utenti</h1>
<p>Benvenuto, <b><?= htmlspecialchars($_SESSION['username']) ?></b> | 
<a href="?logout=1">Logout</a></p>
<p><b><?= htmlspecialchars($msg) ?></b></p>
<?php if (empty($users)): ?>
<p>Nessun utente registrato.</p>
<?php else: ?>
<table border="1" cellpadding="5">
<tr><th>Username</th><th>Nuova Password</th><th>Azioni</th></tr>
<?php foreach ($users as $username => $hash): ?>
<tr>
<form method="post">
    <input type="hidden" name="old_username" value="<?= htmlspecialchars($username) ?>">
    <td><input type="text" name="new_username" value="<?= htmlspecialchars($username) ?>" required></td>
    <td><input type="password" name="new_password" placeholder="Lascia vuoto per non cambiare"></td>
    <td>
        <input type="submit" value="Modifica">
        <a href="?del=<?= urlencode($username) ?>" onclick="return confirm('Eliminare questo utente?')">Elimina</a>
    </td>
</form>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
<p><a href="Registrazione.php">Aggiungi nuovo utente</a></p>
</body>
</html>