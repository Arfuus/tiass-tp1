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

// Vérification du formulaire d'inscription
if (isset($_POST['ajouter'])) {
    $identifiant = trim($_POST['identifiant']);
    $motdepasse  = $_POST['motdepasse'];

    // Vérifier si l'identifiant existe déjà
    $stmt = $pdo->prepare("SELECT identifiant FROM users WHERE identifiant = :identifiant");
    $stmt->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $message = "Identifiant déjà utilisé.";
    } else {
        // Hachage du mot de passe avant insertion
        $hash = password_hash($motdepasse, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (identifiant, motdepasse) VALUES (:identifiant, :motdepasse)");
        $stmt->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);
        $stmt->bindParam(':motdepasse', $hash, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $message = "Compte créé avec succès !";
        } else {
            $message = "Erreur lors de l'inscription.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajout d'un compte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .container {
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
    <div class="container">
        <h2>Ajout de compte</h2>
        <form method="post" action="">
            <input type="text" name="identifiant" placeholder="Identifiant" required><br>
            <input type="password" name="motdepasse" placeholder="Mot de passe" required><br>
            <button type="submit" name="ajouter">Créer compte</button>
            <button type="button" onclick="window.location.href='main.php'">Retour</button>
        </form>
        <?php if ($message !== ""): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
    </div>
</body>

</html>