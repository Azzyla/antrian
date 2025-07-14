<!DOCTYPE html>
<html>
<head>
    <title>Kepuasan Layanan</title>
    <style>
        body {
            background-color: #007bff;
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 400px;
        }
        input, select, textarea, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .alert {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="card">
    <h3 style="text-align:center;">Sistem Antrian PLT</h3>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert"><?= session()->getFlashdata('success'); ?></div>
    <?php endif; ?>

    <form method="post" action="/kepuasan/simpan">
        <input type="text" name="nim" placeholder="Masukkan NIM" required>

        <select name="cs" required>
            <option value="">-- Pilih CS --</option>
            <option value="CS Riska">Counter 4 Riska</option>
            <option value="CS Robi">Counter 3 Robi</option>
            <option value="CS Dayu">Couter 2 Dayu
            <option value="CS Dewi"> Counter 1 Dewi</option>
        </select>

        <label>Penilaian Layanan</label>
        <select name="penilaian" required>
            <option value="1">😡 Sangat Buruk</option>
            <option value="2">😞 Buruk</option>
            <option value="3">😐 Cukup</option>
            <option value="4">🙂 Baik</option>
            <option value="5">😄 Sangat Baik</option>
        </select>

        <textarea name="saran" placeholder="Tulis saran Anda di sini..."></textarea>
        <button type="submit">Kirim</button>
    </form>
</div>

</body>
</html>
