<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        form {
            max-width: 400px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
        }
        button {
            padding: 8px 16px;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <h2>Register</h2>

    <?php if (session()->getFlashdata('validation')): ?>
        <div class="error">
            <?= session()->getFlashdata('validation')->listErrors() ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/save">
        <?= csrf_field() ?>

        <label for="username">Username:</label>
        <input type="text" name="username" value="<?= old('username') ?>" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Register</button>
    </form>

</body>
</html>
