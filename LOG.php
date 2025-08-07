<?php
session_start();
$_SESSION['loggedin'] = true;

// Identifiants (à remplacer par base de données plus tard)
$valid_username = 'admin';
$valid_password = 'ocp2025';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['username'] = $username;
        header("Location: DASH.php");
        exit();
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Connexion - OCP Central</title>
  <style>
    /* Copie identique du style CSS que je t’ai déjà donné pour cohérence */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #0f4c3a, #1e7a5f, #2d8f6b);
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    .login-container {
      background: rgba(255,255,255,0.95);
      padding: 3rem 2rem;
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 420px;
      backdrop-filter: blur(10px);
      text-align: center;
    }

    .login-logo {
      width: 70px;
      height: 70px;
      background: linear-gradient(45deg, #00a86b, #28a745);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
      font-weight: bold;
      color: white;
      margin: 0 auto 1rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    .form-group {
      text-align: left;
      margin-bottom: 1.5rem;
    }

    .form-group label {
      font-weight: 600;
      color: #0d3d2f;
      display: block;
      margin-bottom: 0.5rem;
    }

    .form-group input {
      width: 100%;
      padding: 0.75rem;
      border-radius: 12px;
      border: 1px solid #ccc;
      font-size: 1rem;
    }

    .btn-login {
      width: 100%;
      padding: 0.9rem;
      border: none;
      border-radius: 12px;
      background: linear-gradient(45deg, #00a86b, #28a745);
      color: white;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
    }

    .btn-login:hover {
      background: linear-gradient(45deg, #28a745, #00a86b);
      transform: translateY(-2px);
    }

    .error-message {
      color: #dc3545;
      margin-bottom: 1rem;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-logo">OCP</div>
    <h2>Connexion</h2>
    <p>Accédez à la plateforme</p>

    <?php if ($error): ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <div class="form-group">
        <label for="username">Nom d'utilisateur</label>
        <input type="text" name="username" required />
      </div>

      <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" name="password" required />
      </div>

      <button class="btn-login" type="submit">Se connecter</button>
    </form>
  </div>
</body>
</html>
