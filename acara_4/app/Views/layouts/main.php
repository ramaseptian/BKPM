<!-- app/Views/layouts/main.php -->
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../partials/header.php'; ?>
<body>
    <?php include __DIR__ . '/../partials/navbar.php'; ?>
    <main class="container mt-4">
        <?php require $content; ?>
    </main>
    <?php include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
