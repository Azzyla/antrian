<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tambah User</title>
</head>
<body>

<h2>Tambah User Baru</h2>

<?php if (session()->getFlashdata('validation')): ?>
    <div style="color: red;">
        <?= session()->getFlashdata('validation')->listErrors() ?>
    </div>
<?php endif; ?>

<form action="/kepala/simpan_user" method="POST">
    <?= csrf_field() ?>

    <label>Username:</label><br>
    <input type="text" name="username" value="<?= old('username') ?>" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Role:</label><br>
    <select name="role" required>
        <option value="">-- Pilih Role --</option>
        <option value="cs1">CS 1</option>
        <option value="cs2">CS 2</option>
        <option value="cs3">CS 3</option>
    </select><br><br>

    <button type="submit">Simpan</button>
</form>

</body>
</html>
