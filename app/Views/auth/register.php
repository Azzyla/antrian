<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>

<h2>Register</h2>

<?php if (session()->getFlashdata('validation')): ?>
    <div style="color: red;">
        <?= session()->getFlashdata('validation')->listErrors() ?>
    </div>
<?php endif; ?>

<form method="POST" action="/save">
    <?= csrf_field() ?>
    <label for="username">Username:</label>
    <input type="text" name="username" value="<?= old('username') ?>" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" required><br>

    <button type="submit">Register</button>
</form>

</body>
</html>
