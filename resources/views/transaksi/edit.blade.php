<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 250px; height: 100vh;">
            <a href="#" class="d-flex align-items-center mb-3 text-white text-decoration-none">
                <span class="fs-4">rOtan</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li><a href="#" class="nav-link text-white">Dashboard</a></li>
                <li><a href="{{ route('crud.barang.index') }}" class="nav-link text-white">Barang</a></li>
                <li><a href="{{ route('crud.kategori.index') }}" class="nav-link text-white">Kategori</a></li>
                <li><a href="{{ route('crud.pelanggan.index') }}" class="nav-link text-white">Pengguna</a></li>
                <li><a href="{{ route('transaksi.index') }}" class="nav-link text-white active">Transaksi</a></li>
                <li><a href="#" class="nav-link text-white">Laporan</a></li>
            </ul>
            <hr>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100">Logout</button>
            </form>
        </div>
        <div class="container mt-5">
        <h2 class="mb-4">Detail Transaksi</h2>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white">
                <strong>Informasi Transaksi</strong>
            </div>
            <div class="card-body">
                <p><strong>Nama Pelanggan:</strong> {{ $transaksi->pelanggan->name ?? 'N/A' }}</p>
                <p><strong>Total Harga:</strong> Rp. {{ number_format($transaksi->total_pembelanjaan, 2, ',', '.') }}</p>
                <p><strong>Diskon:</strong> Rp. {{ number_format($transaksi->diskon_nominal, 2, ',', '.') }}</p>
                <p><strong>PPN (12%):</strong> Rp. {{ number_format($transaksi->ppn, 2, ',', '.') }}</p>
                <p><strong>Total Akhir:</strong> Rp. {{ number_format($transaksi->total_akhir, 2, ',', '.') }}</p>
                <p><strong>Uang Dibayar:</strong> Rp. {{ number_format($transaksi->uang_dibayar, 2, ',', '.') }}</p>
                <p><strong>Kembalian:</strong> Rp. {{ number_format($transaksi->kembalian, 2, ',', '.') }}</p>
            </div>
        </div>

        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-dark text-white">
                <strong>Detail Barang</strong>
            </div>
            <div class="card-body">
                @if($transaksi->detail->isEmpty())
                    <div class="alert alert-info text-center">
                        <i class="fas fa-exclamation-circle"></i> Tidak ada detail barang untuk transaksi ini.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered text-center">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi->detail as $detail)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-start"><strong>{{ $detail->barang->nama_barang }}</strong></td>
                                        <td>Rp. {{ number_format($detail->harga, 2, ',', '.') }}</td>
                                        <td>{{ $detail->jumlah }}</td>
                                        <td>Rp. {{ number_format($detail->subtotal, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali ke Daftar Transaksi</a>
        </div>
    </div>
</body>
</html>