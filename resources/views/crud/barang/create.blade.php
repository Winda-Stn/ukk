<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function hitungHargaJual() {
            let hargaBeli = parseFloat(document.getElementById('harga_beli').value);
            if (!isNaN(hargaBeli)) {
                document.getElementById('harga_jual_1').value = hargaBeli + (hargaBeli * 0.10); // Harga Jual 1 (HPP + 10%)
                document.getElementById('harga_jual_2').value = hargaBeli + (hargaBeli * 0.20); // Harga Jual 2 (HPP + 20%)
                document.getElementById('harga_jual_3').value = hargaBeli + (hargaBeli * 0.30); // Harga Jual 3 (HPP + 30%)
            } else {
                document.getElementById('harga_jual_1').value = '';
                document.getElementById('harga_jual_2').value = '';
                document.getElementById('harga_jual_3').value = '';
            }
        }
    </script>
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
                <li><a href="{{ route('crud.barang.index') }}" class="nav-link text-white active">Barang</a></li>
                <li><a href="{{ route('crud.kategori.index') }}" class="nav-link text-white">Kategori</a></li>
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
            <h2>Tambah Barang</h2>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('crud.barang.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Kode Barang</label>
                    <input type="text" name="kode_barang" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="id_kategori" class="form-control" required>
                        <option value="" selected disabled>Pilih Kategori</option>
                        @foreach($kategori as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control" required>
                </div>

                <div class="mb-3">
                <label class="form-label">Tanggal Kadaluarsa</label>
                <input type="date" name="tanggal_kadaluarsa" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Pembelian</label>
                    <input type="date" name="tanggal_pembelian" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="harga_beli" class="form-label">Harga Beli</label>
                    <input type="number" name="harga_beli" id="harga_beli" class="form-control" required oninput="hitungHargaJual()">
                </div>

                <!-- Harga Jual Otomatis -->
                <div class="mb-3">
                    <label for="harga_jual_1" class="form-label">Harga Jual 1 (HPP + 10%)</label>
                    <input type="text" name="harga_jual_1" id="harga_jual_1" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label for="harga_jual_2" class="form-label">Harga Jual 2 (HPP + 20%)</label>
                    <input type="text" name="harga_jual_2" id="harga_jual_2" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label for="harga_jual_3" class="form-label">Harga Jual 3 (HPP + 30%)</label>
                    <input type="text" name="harga_jual_3" id="harga_jual_3" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" name="stok" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Minimal Stok</label>
                    <input type="number" name="min_stok" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('crud.barang.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>
