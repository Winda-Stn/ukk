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
                <li><a href="{{ route('transaksi.index') }}" class="nav-link text-white">Transaksi</a></li>
                <li><a href="{{ route('laporan.index') }}" class="nav-link text-white">Laporan</a></li>
                <li><a href="{{ route('laporann.index') }}" class="nav-link text-white active">Stok</a></li>
            </ul>
            <hr>
            <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger w-100">Logout</button>
            </form>
        </div>

        <div class="container">
        <div class="container my-4">
    <div class="row">
        <!-- Total Item -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="fw-bold">Total Item</h5>
                    <p class="fs-4">11 Item</p>
                </div>
            </div>
        </div>

        <!-- Total Nilai Stok -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="fw-bold">Stok Cukup</h5>
                    <p class="fs-4">7 Item</p>
                </div>
            </div>
        </div>

        <!-- Perlu Restock -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 {{ $items_warning > 0 ? 'bg-danger text-white' : '' }}">
                <div class="card-body text-center">
                    <h5 class="fw-bold">Perlu Restock</h5>
                    <p class="fs-4">4 item</p>
                </div>
            </div>
        </div>
    </div>
</div>


            <!-- Laporan Table -->
            <table class="table table-striped table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Stok</th>
            <th>Minimal Stok</th>
            <th>Harga Beli</th>
            <th>Total Nilai</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($laporan as $row)
        @php
            $total_nilai = $row->stok * $row->harga_beli;
            $status = $row->stok <= 10 ? 'warning' : 'normal';
        @endphp
        <tr class="{{ $status == 'warning' ?  : '' }}">
            <td>{{ $row->kode_barang }}</td>
            <td>{{ $row->nama_barang }}</td>
            <td>{{ $row->kategori->nama_kategori ?? '-' }}</td>
            <td>{{ number_format($row->stok) }}</td>
            <td>{{ number_format($row->min_stok) }}</td>
            <td>Rp {{ number_format($row->harga_beli, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($total_nilai, 0, ',', '.') }}</td>
            <td>
                @if($row->stok <= 10)
                    <span class="badge bg-danger">Perlu Restock</span>
                @else
                    <span class="badge bg-success">Normal</span>
                @endif
            </td>
        </tr>
    @endforeach
</tbody>

</table>

        </div>
    </div>

    <script>
        function exportToExcel() {
            window.location.href = "{{ route('laporan.export') }}";
        }
    </script>
</body>
</html>
