<!DOCTYPE html>
<html lang="id">
<head>
    <title>Laporan Penjualan</title>
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
                <li><a href="#" class="nav-link text-white ">Dashboard</a></li>
                <li><a href="{{ route('crud.barang.index') }}" class="nav-link text-white">Barang</a></li>
                <li><a href="{{ route('crud.kategori.index') }}" class="nav-link text-white">Kategori</a></li>
                <li><a href="{{ route('crud.pelanggan.index') }}" class="nav-link text-white">Pengguna</a></li>
                <li><a href="{{ route('transaksi.index') }}" class="nav-link text-white">Transaksi</a></li>
                <li><a href="{{ route('laporan.index') }}" class="nav-link text-white active">Laporan</a></li>
                <li><a href="{{ route('laporann.index') }}" class="nav-link text-white ">Stok</a></li>
            </ul>
            <hr>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100">Logout</button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="container-fluid p-4">
            <h1>Laporan Penjualan</h1>
            
            @if(auth()->user()->role === 'kasir')
                <div class="alert alert-danger">Kasir: {{ auth()->user()->name }}</div>
            @endif

            <div class="d-flex justify-content-between mb-3">
                <a href="{{ route('laporan.export', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-success">Export Excel</a>
                <button onclick="window.print()" class="btn btn-primary">Print</button>
            </div>

            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <label for="start_date">Tanggal Awal:</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date">Tanggal Akhir:</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-secondary">Filter</button>
                    </div>
                </div>
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal/Waktu</th>
                        <th>Kasir</th>
                        <th>Pelanggan</th>
                        <th>Tipe</th>
                        <th>Total Pembelian</th>
                        <th>Diskon</th>
                        <th>Poin Digunakan</th>
                        <th>Total Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporan as $row)
                    <tr>
                        <td>{{ date('Y-m-d H:i:s', strtotime($row->tanggal_transaksi)) }}</td>
                        <td>{{ $row->kasir->name ?? '-' }}</td>
                        <td>{{ $row->pelanggan->name ?? '-' }}</td>
                        <td>{{ $row->tipe_pelanggan ?? '-' }}</td>
                        <td>Rp {{ number_format($row->total_pembelanjaan, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($row->diskon_nominal, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($row->poin_digunakan, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($row->total_akhir, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Total:</td>
                        <td>Rp {{ number_format($total_penjualan, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($total_diskon, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($total_poin, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($total_akhir, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
</html>
