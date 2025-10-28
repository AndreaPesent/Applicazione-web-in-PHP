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
if (isset($_GET['logout'])) 
{
    session_destroy();
    header("Location: LoginApp.php");
    exit();
}
if (isset($_GET['del']))
 {
    $del = $_GET['del'];
    if (isset($users[$del])) 
    {
        unset($users[$del]);
        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
        $msg = "Utente eliminato.";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $old = $_POST['old_username'];
    $newPass = trim($_POST['new_password']);
    if ($newPass === "")
    {
        $msg = "Inserisci una nuova password.";
    } else {
        $users[$old] = password_hash($newPass, PASSWORD_DEFAULT);
        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
        $msg = "Password aggiornata.";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Utenti</title>
</head>
<body>
<h1>Gestione Utenti</h1>
<p>Ciao, <b><?= htmlspecialchars($_SESSION['username']) ?></b> |
<a href="?logout=1">Logout</a></p>
<?php if ($msg): ?>
<p><b><?= htmlspecialchars($msg) ?></b></p>
<?php endif; ?>
<h2>Utenti registrati</h2>
<?php if (empty($users)): ?>
<p>Nessun utente registrato.</p>
<?php else: ?>
    <?php foreach ($users as $username => $hash): ?>
        <form method="post" style="margin-bottom:10px;">
            <b><?= htmlspecialchars($username) ?></b><br>
            Nuova password:
            <input type="password" name="new_password" placeholder="Lascia vuoto per non cambiare">
            <input type="hidden" name="old_username" value="<?= htmlspecialchars($username) ?>">
            <input type="submit" value="Aggiorna">
            <a href="?del=<?= urlencode($username) ?>" onclick="return confirm('Eliminare questo utente?')">Elimina</a>
        </form>
    <?php endforeach; ?>
<?php endif; ?>
<p><a href="Registrazione.php">Aggiungi nuovo utente</a></p>
</body>
</html>