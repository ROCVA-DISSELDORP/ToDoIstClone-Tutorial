<!DOCTYPE html>
<html>
<head>
    <title>Login - Todoist Clone</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .login-card { width: 300px; margin: 100px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .login-card input { width: 100%; margin-bottom: 10px; padding: 8px; box-sizing: border-box; }
        .login-card button { width: 100%; background: #db4c3f; color: white; border: none; padding: 10px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Inloggen</h2>
        <?php if(isset($error)): ?><p style="color:red"><?= $error ?></p><?php endif; ?>
        <form action="<?= url('/login') ?>" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Wachtwoord" required>
            <button type="submit">Log in</button>
        </form>
    </div>
</body>
</html>