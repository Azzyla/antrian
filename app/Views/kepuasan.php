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
            margin: 0;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        input, select, textarea, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
            box-sizing: border-box;
        }

        .alert {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .emoji-options {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
        }

        .emoji-label {
            font-size: 24px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .emoji-label:hover {
            transform: scale(1.2);
        }

        .emoji-input {
            display: none;
        }

        .emoji-input:checked + .emoji-label {
            border: 2px solid #007bff;
            border-radius: 50%;
            padding: 5px;
        }
    </style>
</head>
<body>

<div class="card">
    <h3 style="text-align:center;">Kepuasan Layanan PLT</h3>

    <!-- Flash Message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert"><?= session()->getFlashdata('success'); ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="error"><?= session()->getFlashdata('error'); ?></div>
    <?php endif; ?>

    <?php if (!empty(session('errors'))): ?>
        <div class="error">
            <ul>
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <form method="post" action="<?= base_url('kepuasan/simpan') ?>">
        <?= csrf_field() ?>

        <!-- Nomor Antrian -->
        <input type="text" name="nomor_antrian" placeholder="Masukkan Nomor Antrian" required value="<?= old('nomor_antrian') ?>">

        <!-- Penilaian -->
        <label>Penilaian Layanan</label>
        <div class="emoji-options">
            <input type="radio" id="sangat_buruk" name="penilaian" value="1" class="emoji-input" <?= old('penilaian') == 1 ? 'checked' : '' ?>>
            <label for="sangat_buruk" class="emoji-label" title="Sangat Buruk">😡</label>

            <input type="radio" id="buruk" name="penilaian" value="2" class="emoji-input" <?= old('penilaian') == 2 ? 'checked' : '' ?>>
            <label for="buruk" class="emoji-label" title="Buruk">😞</label>

            <input type="radio" id="cukup" name="penilaian" value="3" class="emoji-input" <?= old('penilaian') == 3 ? 'checked' : '' ?>>
            <label for="cukup" class="emoji-label" title="Cukup">😐</label>

            <input type="radio" id="baik" name="penilaian" value="4" class="emoji-input" <?= old('penilaian') == 4 ? 'checked' : '' ?>>
            <label for="baik" class="emoji-label" title="Baik">🙂</label>

            <input type="radio" id="sangat_baik" name="penilaian" value="5" class="emoji-input" <?= old('penilaian') == 5 ? 'checked' : '' ?>>
            <label for="sangat_baik" class="emoji-label" title="Sangat Baik">😄</label>
        </div>

    
        <!-- Tombol Kirim -->
        <button type="submit">Kirim</button>
    </form>
</div>

</body>
</html>
