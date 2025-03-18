<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Style tambahan untuk merapikan tampilan */
        .table th, .table td {
            text-align: center; /* Rata tengah */
            vertical-align: middle; /* Tengah secara vertikal */
        }
        .btn-action {
            display: flex;
            justify-content: center;
            gap: 5px;
        }
        .table-responsive {
            max-height: 70vh; /* Supaya tidak terlalu panjang */
            overflow-y: auto;
        }
    </style>
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
            <h2 class="mb-3">Daftar Barang</h2>
            <a href="{{ route('crud.barang.create') }}" class="btn btn-primary mb-3">Tambah Barang</a>
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Kadaluarsa</th>
                            <th>Pembelian</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual 1</th>
                            <th>Harga Jual 2</th>
                            <th>Harga Jual 3</th>
                            <th>Stok</th>
                            <th>Min Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barang as $item)
                        <tr>
                            <td>{{ $item->kode_barang }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->tanggal_kadaluarsa }}</td>
                            <td>{{ $item->tanggal_pembelian }}</td>
                            <td>Rp{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($item->harga_jual_1, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($item->harga_jual_2, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($item->harga_jual_3, 0, ',', '.') }}</td>
                            <td>{{ $item->stok }}</td>
                            <td>{{ $item->min_stok }}</td>
                            <td class="btn-action">
                                <a href="{{ route('crud.barang.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('crud.barang.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
