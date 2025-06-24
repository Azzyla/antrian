<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class=" d-flex bg-primary align-items-center justify-content-center" style="height: 100vh;">

    <div class="card shadow p-4" style="min-width: 400px;">
        <div class="text-center mb-4" >
       <img src="<?= base_url('logo_itp.png') ?>" alt="Logo" class="mb-2" width="120" height="100">

            <h5 class="mb-3">KEPALA PLT</h5>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/kepala/login">

            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label d-flex justify-content-between">
                    <span>Password</span>
                    <!-- <a href="#" class="text-decoration-none" style="font-size: 0.9em;">Lupa Password</a> -->
                </label>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>