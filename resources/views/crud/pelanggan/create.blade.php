<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 250px; height: 100vh; position: fixed;">
            <a href="#" class="d-flex align-items-center mb-3 text-white text-decoration-none">
                <span class="fs-4">rOtan</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li><a href="#" class="nav-link text-white">Dashboard</a></li>
                <li><a href="{{ route('crud.barang.index') }}" class="nav-link text-white">Barang</a></li>
                <li><a href="{{ route('crud.kategori.index') }}" class="nav-link text-white">Kategori</a></li>
                <li><a href="{{ route('crud.pelanggan.index') }}" class="nav-link text-white active">Pengguna</a></li>
                <li><a href="{{ route('transaksi.index') }}" class="nav-link text-white">Transaksi</a></li>
                <li><a href="{{ route('laporan.index') }}" class="nav-link text-white">Laporan</a></li>
                <li><a href="{{ route('laporann.index') }}" class="nav-link text-white">Stok</a></li>
            </ul>
            <hr>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100">Logout</button>
            </form>
        </div>

        <!-- Konten -->
        <div class="container mt-4" style="margin-left: 270px; width: calc(100% - 270px);">
            <h2 class="mb-4">Tambah Pelanggan</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('crud.pelanggan.store') }}" method="POST" class="p-4 bg-white shadow rounded">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Nama:</label>
                    <input type="text" name="nama" required class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" name="email" required class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password:</label>
                    <input type="password" name="password" required class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password:</label>
                    <input type="password" name="password_confirmation" required class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Role:</label>
                    <select name="role" id="role" required class="form-select">
                        <option value="pelanggan">Pelanggan</option>
                        <option value="admin">Admin</option>
                        <option value="kasir">Kasir</option>
                    </select>
                </div>

                <div class="mb-3" id="tipe_pelanggan_field" style="display: none;">
                    <label class="form-label">Tipe Pelanggan:</label>
                    <select name="tipe_pelanggan" required>
                     <option value="tipe_1">Tipe 1</option>
                    <option value="tipe_2">Tipe 2</option>
                    <option value="tipe_3">Tipe 3</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">Simpan</button>
            </form>
        </div>
    </div>

    <script>
    document.getElementById('role').addEventListener('change', function() {
        let tipePelangganField = document.getElementById('tipe_pelanggan_field');
        tipePelangganField.style.display = (this.value === 'pelanggan') ? 'block' : 'none';
    });

    document.addEventListener('DOMContentLoaded', function() {
        let roleSelect = document.getElementById('role');
        let tipePelangganField = document.getElementById('tipe_pelanggan_field');
        if (roleSelect.value === 'pelanggan') {
            tipePelangganField.style.display = 'block';
        }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
