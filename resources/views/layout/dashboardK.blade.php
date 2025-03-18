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
                <li><a href="#" class="nav-link text-white active">Dashboard</a></li>
                <li><a href="{{ route('crud.pelanggan.index') }}" class="nav-link text-white">Pengguna</a></li> <!-- ✅ Pastikan route tersedia -->
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
        
        <!-- Main Content -->
        <div class="container-fluid p-4" style="width: 100%">
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-primary text-white mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Barang</h5>
                            <p class="card-text">11</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Transaksi</h5>
                            <p class="card-text">5</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Stok Hampir Habis</h5>
                            <p class="card-text">4</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <canvas id="salesChart"></canvas>
                </div>
                <div class="col-md-6">
                    <h5 class="mt-3">Tabel Stok Barang</h5>
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <td>1</td>
                                <td>Sapu</td>
                                <td>Furtinur</td>
                                <td>4</td>
                            </tr>
                            <tr>
                            <td>2</td>
                                <td>Cincin</td>
                                <td>Aksesoris</td>
                                <td>5</td>
                            </tr>
                            <tr><td>3</td>
                                <td>Vas</td>
                                <td>Dekorasi</td>
                                <td>7</td>
                            </tr>
                            <tr>
                            <td>4</td>
                                <td>Pigura</td>
                                <td>Dekorasi</td>
                                <td>9</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>  
        </div>
    </div>


    <script>
        const ctxSales = document.getElementById('salesChart').getContext('2d');
        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Penjualan',
                    data: [400, 300, 500, 700, 600, 800],
                    borderColor: 'blue',
                    borderWidth: 2
                }]
            }
        });
    </script>
</body>
</html>
