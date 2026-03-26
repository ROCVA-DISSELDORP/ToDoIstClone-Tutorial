<?php
require_once __DIR__ . '/../app/Controllers/AuthController.php';

$error = null;

// Als je al ingelogd bent, ga naar de app.
if (isset($_SESSION['user_id'])) {
    header('Location: /');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (register($name, $email, $password)) {
        header('Location: ./login.php');
        exit;
    } else {
        $error = 'Registratie mislukt. Email is mogelijk al in gebruik.';
    }
}
?>

<?php include __DIR__ . '/../resources/views/partials/header.php'; ?>
<body class="min-h-screen flex items-center justify-center bg-gray-50 px-4 py-10 font-sans text-gray-800">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Registreren</h2>
        <p class="text-sm text-gray-600 mb-6">Maak een account aan om Todoist Clone te gebruiken.</p>

        <?php if (isset($error)): ?>
            <p class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-2 text-sm" role="alert">
                <?= $error ?>
            </p>
        <?php endif; ?>

        <form action="./register.php" method="POST" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Naam</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    placeholder="Je naam"
                    required
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-base outline-none transition focus:border-red-500 focus:ring-4 focus:ring-red-100"
                >
            </div>

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
                Account aanmaken
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Heb je al een account?
            <a href="./login.php" class="text-red-600 hover:underline font-medium">Log in</a>
        </p>
    </div>
</body>
<?php include __DIR__ . '/../resources/views/partials/footer.php'; ?>
