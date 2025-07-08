<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola User - Kepala PLT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding: 1rem;
            min-height: 100vh;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin-bottom: 1rem;
        }

        .sidebar a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                min-height: auto;
            }
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="text-center mb-4">
                <h4>Sistem Antrian</h4>
                <img src="<?= base_url('logo_itp.png') ?>" width="80" alt="Logo">
            </div>
            <a href="<?= base_url('kepala/dashboard') ?>">Dashboard</a>
            <a href="<?= base_url('kepala/rekap_antrian') ?>">Rekap Antrian</a>
            <a href="<?= base_url('kepala/rekap_kepuasan') ?>">Rekap Kepuasan</a>
            <a href="<?= base_url('kepala/kelola_user') ?>">Kelola User</a>
            <a href="<?= base_url('kepala/logout') ?>" class="btn btn-outline-light w-100 mt-5">Logout</a>
        </div>

        <!-- Main Content -->
        <main class="flex-grow-1 p-4">
            <h2 class="text-primary mb-4">Kelola User</h2>

            <!-- Form Tambah User -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Tambah User Baru</div>
                <div class="card-body">
                    <form method="POST" action="<?= base_url('kepala/simpan_user') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="cs1">CS 1</option>
                                <option value="cs2">CS 2</option>
                                <option value="cs3">CS 3</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </form>
                </div>
            </div>

            <!-- Daftar User -->
            <div class="card">
                <div class="card-header bg-secondary text-white">Daftar User</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= esc($user['username']) ?></td>
                                    <td><?= esc(strtoupper($user['role'])) ?></td>
                                    <td>
                                        <a href="<?= base_url('/kepala/delete/' . $user['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus user ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>

</html>