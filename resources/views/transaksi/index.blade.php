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
        <div class="container mt-3 py-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <strong><i class="fas fa-shopping-cart"></i> Daftar Transaksi Penjualan</strong>
                    <a href="{{ route('transaksi.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Tambah Transaksi
                    </a>
                </div>
                <div class="card-body">
                    @if($transaksi->isEmpty())
                        <div class="alert alert-info text-center">
                            <i class="fas fa-exclamation-circle"></i> Tidak ada data transaksi.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered text-center">
                                <thead class="bg-light text-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Total Harga</th>
                                        <th>Diskon</th>
                                        <th>PPN</th>
                                        <th>Total Akhir</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaksi as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-start"><strong>{{ $item->pelanggan->name ?? 'N/A' }}</strong></td>
                                            <td>
                                                <strong class="text-primary">
                                                    Rp. {{ number_format($item->total_pembelanjaan, 2, ',', '.') }}
                                                </strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-danger text-white p-2">
                                                    Rp. {{ number_format($item->diskon_nominal, 2, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info text-white p-2">
                                                    Rp. {{ number_format($item->ppn, 2, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success text-white p-2">
                                                    Rp. {{ number_format($item->total_akhir, 2, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>
                                                <!-- Tombol Lihat Detail -->
                                                <a href="{{ route('transaksi.show', $item->id) }}" 
                                                   class="btn btn-info btn-sm" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <!-- Tombol Delete (destroy) -->
                                                <form action="{{ route('transaksi.destroy', $item->id) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</body>
</html>