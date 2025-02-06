<?php
session_start();

$host = 'localhost';
$dbname = 'tiass_tp1';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

$message = "";

// Vérification du formulaire de connexion
if (isset($_POST['valider'])) {
    $identifiant = trim($_POST['identifiant']);
    $motdepasse  = $_POST['motdepasse'];

    // Vérification si l'identifiant existe
    $stmt = $pdo->prepare("SELECT motdepasse FROM users WHERE identifiant = :identifiant");
    $stmt->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($motdepasse, $user['motdepasse'])) {
        $message = "ok"; // Connexion réussie
    } else {
        $message = "erreur"; // Identifiants incorrects
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Page de Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .login-container {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
        }

        input {
            width: 80%;
            padding: 8px;
            margin: 5px 0;
        }

        button {
            padding: 8px 12px;
            margin: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h1>Mon Logo</h1>
        <form method="post" action="">
            <input type="text" name="identifiant" placeholder="Identifiant" required><br>
            <input type="password" name="motdepasse" placeholder="Mot de passe" required><br>
            <button type="reset">Reset</button>
            <button type="submit" name="valider">Valider</button>
            <button type="button" onclick="window.location.href='ajout_compte.php'">Ajout Compte</button>
        </form>
        <?php if ($message !== ""): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
    </div>
</body>

</html>