<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori</title>
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
                <li><a href="{{ route('crud.kategori.index') }}" class="nav-link text-white ">Kategori</a></li>
                <li><a href="{{ route('crud.pelanggan.index') }}" class="nav-link text-white">Pengguna</a></li>
                <li><a href="{{ route('transaksi.index') }}" class="nav-link text-white">Transaksi</a></li>
                <li><a href="{{ route('laporan.index') }}" class="nav-link text-white">Laporan</a></li>
                <li><a href="{{ route('laporann.index') }}" class="nav-link text-white">Stok</a></li>
            </ul>
            <hr>
            <a href="#" class="d-flex align-items-center text-white text-decoration-none">
                <strong>Logout</strong>
            </a>
        </div>

        <!-- Main Content -->
        <div class="container-fluid p-4" style="margin-left: 260px; width: 100%;">
            <h2>Edit Kategori</h2>
            <form action="{{ route('crud.kategori.update', $kategori->id) }}" method="POST">
                @csrf
                @method('PUT') 

                <div class="mb-3">
                    <label class="form-label">Kode Kategori</label>
                    <input type="text" name="kode_kategori" class="form-control" value="{{ $kategori->kode_kategori }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="form-control" value="{{ $kategori->nama_kategori }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('crud.kategori.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>
