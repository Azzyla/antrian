<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ambil Nomor Antrian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e0f0ff;
        }

        .top-bar {
            background-color: #007bff;
            color: white;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
        }

        .antrian-box {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 20px;
            width: 220px;
            text-align: center;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.3);
        }

        .antrian-box h5 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .jumlah {
            font-size: 36px;
            margin: 10px 0;
        }

        .btn-white {
            background-color: #ffffff;
            color: #007bff;
            border: 2px solid #007bff;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 8px;
            width: 100%;
            text-align: center;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-white:hover {
            background-color: #f0f0f0;
            color: #0056b3;
            border-color: #0056b3;
        }

        .btn-white:active {
            background-color: #e0e0e0;
            color: #003366;
            border-color: #003366;
        }

        .btn-white.disabled {
            background-color: #e0e0e0;
            color: #b0b0b0;
            border-color: #dcdcdc;
            pointer-events: none;
        }

        #messageToast {
            margin: 30px auto 0 auto;
            max-width: 400px;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: white;
            background: linear-gradient(135deg, #00b894, #55efc4);
            text-align: center;
            opacity: 1;
            transform: translateY(0);
            transition: all 0.4s ease-in-out;
        }

        .toast-error {
            background: linear-gradient(135deg, #d63031, #e17055);
        }
    </style>
</head>
<body>

    <div class="top-bar">
        Sistem Antrian Pusat Layanan Terpadu PLT
    </div>

    <div class="container my-5">
        <h4 class="text-center mb-4">Ambil Nomor Antrian</h4>

        <?php
        $aktif = session('kategoriAktif');
        $diambil = session()->getFlashdata('kategoriDiambil');

        function tombolClass($kategori, $aktif, $diambil) {
            if ($diambil === $kategori || $aktif !== $kategori) return 'disabled';
            return '';
        }

        function tombolText($kategori, $diambil) {
            return ($diambil === $kategori) ? 'Sudah Diambil' : 'Ambil Nomor';
        }
        ?>

        <div class="d-flex justify-content-center gap-4 flex-wrap">

            <!-- Mahasiswa -->
            <div class="antrian-box">
                <h5>Mahasiswa</h5>
                <div class="icon-user">👤</div>
                <div class="jumlah"><?= esc($antrian['mahasiswa']) ?></div>
                <button id="btn-mahasiswa"
                        onclick="handleAmbilNomor('mahasiswa')"
                        class="btn btn-white <?= tombolClass('mahasiswa', $aktif, $diambil) ?>">
                    <?= tombolText('mahasiswa', $diambil) ?>
                </button>
            </div>

            <!-- Umum -->
            <div class="antrian-box">
                <h5>Umum</h5>
                <div class="icon-user">👥</div>
                <div class="jumlah"><?= esc($antrian['umum']) ?></div>
                <button id="btn-umum"
                        onclick="handleAmbilNomor('umum')"
                        class="btn btn-white <?= tombolClass('umum', $aktif, $diambil) ?>">
                    <?= tombolText('umum', $diambil) ?>
                </button>
            </div>

            <!-- Dosen/Karyawan -->
            <div class="antrian-box">
                <h5>Dosen/Karyawan</h5>
                <div class="icon-user">👨‍🏫</div>
                <div class="jumlah"><?= esc($antrian['dosen']) ?></div>
                <button id="btn-dosen"
                        onclick="handleAmbilNomor('dosen')"
                        class="btn btn-white <?= tombolClass('dosen', $aktif, $diambil) ?>">
                    <?= tombolText('dosen', $diambil) ?>
                </button>
            </div>

        </div>

        <!-- Flash Message -->
        <?php
        $success = session()->getFlashdata('success');
        $error = session()->getFlashdata('error');
        $message = $success ?: $error;
        $toastClass = $error ? 'toast-error' : '';
        if ($message): ?>
            <div id="messageToast" class="<?= $toastClass ?>"><?= esc($message) ?></div>
        <?php endif; ?>
    </div>

    <script>
        function handleAmbilNomor(kategori) {
            window.location.href = "<?= base_url('antrian/ambil/') ?>" + kategori;
        }
    </script>

</body>
</html>
