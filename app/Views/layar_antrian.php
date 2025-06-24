<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Layar Antrian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f9ff;
            font-family: 'Segoe UI', sans-serif;
        }

        .box-antrian {
            background-color: #007bff;
            color: white;
            border-radius: 16px;
            padding: 20px;
            margin: 10px;
            width: 250px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            flex: 1;
        }

        .box-antrian h4 {
            margin: 0;
            font-size: 20px;
        }

        .box-antrian .nomor {
            font-size: 36px;
            font-weight: bold;
            margin: 10px 0;
        }

        .video {
            margin: 20px auto;
            display: flex;
            justify-content: center;
        }

        iframe {
            width: 90%;
            height: 400px;
            border-radius: 12px;
        }

        .row.justify-content-center {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>

    <!-- Video di atas, ukuran besar -->
    <div class="video">
        <iframe 
            src="https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1&mute=1&loop=1&playlist=dQw4w9WgXcQ"
            title="Video Informasi"
            allow="autoplay; encrypted-media" allowfullscreen>
        </iframe>
    </div>

    <!-- Kotak Antrian -->
    <div class="container mt-4">
    <div class="row justify-content-center">

<!-- Box Mahasiswa -->
<div class="box-antrian">
<h4>Mahasiswa</h4>
<div class="nomor">
<?= !empty($mahasiswa) ? 'A' . str_pad($mahasiswa['nomor_antrian'], 2, '0', STR_PAD_LEFT) : '-' ?>
</div>
</div>

<!-- Box Umum -->
<div class="box-antrian">
<h4>Umum</h4>
<div class="nomor">
<?= !empty($umum) ? 'C' . str_pad($umum['nomor_antrian'], 2, '0', STR_PAD_LEFT) : '-' ?>
</div>
</div>

<!-- Box Dosen/Karyawan -->
<div class="box-antrian">
<h4>Dosen/Karyawan</h4>
<div class="nomor">
 <?= !empty($dosen) ? 'B' . str_pad($dosen['nomor_antrian'], 2, '0', STR_PAD_LEFT) : '-' ?>
</div>
</div>


 </div>
</div>
</body>
</html>
