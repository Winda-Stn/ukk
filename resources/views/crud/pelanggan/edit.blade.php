<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
                <li><a href="{{ route('crud.pelanggan.index')}}" class="nav-link text-white active">Pengguna</a></li>
                <li><a href="{{ route('transaksi.index') }}" class="nav-link text-white">Transaksi</a></li>
                <li><a href="{{ route('laporan.index') }}" class="nav-link text-white">Laporan</a></li>
                <li><a href="{{ route('laporann.index') }}" class="nav-link text-white">Stok</a></li>
            </ul>
            <hr>
            <a href="#" class="d-flex align-items-center text-white text-decoration-none">
                <strong>Logout</strong>
            </a>
        </div>

        <!-- Konten -->
        <div class="container mt-4" style="margin-left: 270px; width: calc(100% - 270px);">
            <h1>Edit Pelanggan</h1>
            <form action="{{ route('crud.pelanggan.update', $pelanggan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama Pelanggan -->
                <div class="mb-3">
                    <label class="form-label">Nama Pelanggan</label>
                    <input type="text" name="nama" class="form-control" value="{{ $pelanggan->name }}" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $pelanggan->email }}" required>
                </div>

                <!-- Tipe Pelanggan -->
                <div class="mb-3">
                    <label class="form-label">Tipe Pelanggan</label>
                    <select name="tipe_pelanggan" class="form-control" required>
                        <option value="tipe_1" {{ $pelanggan->tipe_pelanggan == 'tipe_1' ? 'selected' : '' }}>Tipe 1</option>
                        <option value="tipe_2" {{ $pelanggan->tipe_pelanggan == 'tipe_2' ? 'selected' : '' }}>Tipe 2</option>
                        <option value="tipe_3" {{ $pelanggan->tipe_pelanggan == 'tipe_3' ? 'selected' : '' }}>Tipe 3</option>
                    </select>
                </div>

                <!-- Member -->
                <div class="mb-3">
                    <label class="form-label">Member</label>
                    <select name="member" class="form-control">
                        <option value="1" {{ $pelanggan->member ? 'selected' : '' }}>Ya</option>
                        <option value="0" {{ !$pelanggan->member ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>

                <!-- Poin -->
                <div class="mb-3">
                    <label class="form-label">Poin</label>
                    <input type="number" name="poin_pelanggan" class="form-control" value="{{ $pelanggan->poin_pelanggan }}" required>
                </div>

                <!-- Tombol -->
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('crud.pelanggan.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>
