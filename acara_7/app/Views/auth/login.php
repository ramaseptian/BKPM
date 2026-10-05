<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #111827 0%, #1e3a8a 55%, #3b82f6 100%);
        }
        .login-card {
            width: 380px;
            background: #fff;
            border-radius: 16px;
            padding: 36px 32px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.25);
        }
        .login-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: #eff6ff;
            color: #3b82f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin: 0 auto 16px;
        }
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59,130,246,0.15);
        }
        .btn-login {
            background: #3b82f6;
            border: none;
            padding: 10px;
            font-weight: 600;
        }
        .btn-login:hover {
            background: #2563eb;
        }
        .login-hint {
            font-size: 0.8rem;
            color: #9ca3af;
            text-align: center;
            margin-top: 18px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-icon">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <h4 class="text-center fw-bold mb-1">Politeknik Negeri Jember</h4>
        <p class="text-center text-muted mb-4" style="font-size:0.9rem;">Sistem Informasi Akademik</p>

        <?php if (!empty($flash)) : ?>
            <div class="alert alert-success py-2 mb-3" data-flash style="font-size:0.88rem;">
                <i class="bi bi-check-circle-fill me-1"></i>
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger py-2 mb-3" style="font-size:0.88rem;">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('login') ?>">
            <div class="mb-3">
                <label class="form-label small text-muted">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control border-start-0" required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label small text-muted">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control border-start-0" required>
                </div>
            </div>
            <button type="submit" class="btn btn-login btn-primary w-100 text-white">
                <i class="bi bi-box-arrow-in-right me-1"></i> Login
            </button>
        </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>
</html>
