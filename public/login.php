<?php
require_once __DIR__ . '/../app/Controllers/AuthController.php';

$error = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if (login($email, $password)) {
        header('Location: ./index.php');
        exit;
    } else {
        $error = 'Ongeldige email of wachtwoord';
    }
}
?>
<?php include __DIR__ . '/../resources/views/partials/header.php'; ?>
<body class="min-h-screen flex items-center justify-center bg-gray-50 px-4 py-10 font-sans text-gray-800">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Inloggen</h2>
        <p class="text-sm text-gray-600 mb-6">Log in om door te gaan.</p>

        <?php if (isset($error)): ?>
            <p class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-2 text-sm" role="alert">
                <?= $error ?>
            </p>
        <?php endif; ?>

        <form action="./login.php" method="POST" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    placeholder="you@example.com"
                    required
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-base outline-none transition focus:border-red-500 focus:ring-4 focus:ring-red-100"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Wachtwoord</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    required
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-base outline-none transition focus:border-red-500 focus:ring-4 focus:ring-red-100"
                >
            </div>

            <button
                type="submit"
                class="w-full rounded-xl bg-red-600 px-4 py-2.5 text-white font-semibold text-base transition hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-100"
            >
                Log in
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Nog geen account?
            <a href="./register.php" class="text-red-600 hover:underline font-medium">Registreer</a>
        </p>
    </div>
</body>
<?php include __DIR__ . '/../resources/views/partials/footer.php'; ?>