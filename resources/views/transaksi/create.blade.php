<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="d-flex">
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
            <form action="{{ route('transaksi.store') }}" method="POST" id="form-transaksi">
                @csrf
                
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <div class="card shadow-sm mb-3">
                            <div class="card-header bg-dark text-white">
                                <strong>Product</strong>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="pelanggan_id">Tipe Pelanggan</label>
                                    <select name="pelanggan_id" class="form-control" id="pelanggan" onchange="hitungTotal()">
                                        <option value="">Pilih Pelanggan</option>
                                        @foreach ($pelanggan as $p)
                                            <option value="{{ $p->id }}"
                                                    data-tipe="{{ $p->tipe_pelanggan }}"
                                                    data-membership_points="{{ $p->membership_points }}">
                                                {{ $p->name }} (Tipe: {{ $p->tipe_pelanggan }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex align-items-center">
                                        <label class="me-2">Pilih Produk:</label>
                                        <select id="select-produk" class="form-control me-2">
                                            <option value="">-- Pilih Item --</option>
                                            @foreach ($barang as $item)
                                                <option value="{{ $item->id }}"
                                                        data-harga-jual-1="{{ $item->harga_jual_1 }}"
                                                        data-harga-jual-2="{{ $item->harga_jual_2 }}"
                                                        data-harga-jual-3="{{ $item->harga_jual_3 }}"
                                                        data-stock="{{ $item->stok }}">
                                                    {{ $item->nama_barang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> 
                                    <div class="d-flex align-items-center mt-2">
                                        <label class="me-2">Qty:</label>
                                        <input type="number" id="qty-produk" class="form-control me-2" value="1" min="1" oninput="validateQuantity()">
                                        <button type="button" class="btn btn-dark" id="btn-tambah-produk" onclick="tambahProduk()">Tambah Produk</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card shadow-sm">
                            <div class="card-header bg-dark text-white">
                                <strong>Keranjang</strong>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover text-center" id="keranjang-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Barang</th>
                                                <th>Harga</th>
                                                <th>Qty</th>
                                                <th>Subtotal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header bg-dark text-white">
                                <strong>Pembayaran</strong>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label>Poin Membership Dimiliki:</label>
                                    <span id="label-membership-owned" class="fw-bold">0</span>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="membership_points">Poin Membership yang Digunakan (opsional)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="membership_points" name="poin_digunakan" value="0" oninput="hitungTotal()" min="0">
                                        <button type="button" class="btn btn-outline-secondary" onclick="useMaxMembership()">Gunakan 50% Membership</button>
                                    </div>
                                    <div id="membership-warning" class="text-danger mt-1" style="display:none;">
                                        Membership points tidak mencukupi
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="payment_amount">Pembayaran</label>
                                    <input type="number" class="form-control" id="payment_amount" name="uang_dibayar" value="0" min="0" oninput="hitungKembalian()">
                                </div>
                                
                                <div class="mb-2">
                                    <label>Total Sebelum Diskon: Rp.</label>
                                    <span id="label-total" class="fw-bold">0</span>
                                </div>
                                <div class="mb-2">
                                    <label>Diskon: Rp.</label>
                                    <span id="label-diskon" class="fw-bold">0</span>
                                </div>
                                <div class="mb-2">
                                    <label>PPN (12%): Rp.</label>
                                    <span id="label-ppn" class="fw-bold">0</span>
                                </div>
                                <div class="mb-2">
                                    <label>Total Akhir (Setelah Diskon & PPN): Rp.</label>
                                    <span id="label-total-akhir" class="fw-bold">0</span>
                                </div>
                                <div class="mb-2">
                                    <label>Poin Membership yang Digunakan: Rp.</label>
                                    <span id="label-membership-used" class="fw-bold">0</span>
                                </div>
                                <div class="mb-2">
                                    <label>Poin Membership Didapat:</label>
                                    <span id="label-membership-earned" class="fw-bold">0</span>
                                </div>
                                <div class="mb-2">
                                    <label>Total Bayar (Setelah Redeem Membership): Rp.</label>
                                    <span id="label-grand-total" class="fw-bold">0</span>
                                </div>
                                <div class="mb-2">
                                    <label>Kembalian: Rp.</label>
                                    <span id="label-kembalian" class="fw-bold">0</span>
                                    <div id="payment-warning" class="text-danger mt-1" style="display:none;">
                                        Uang tidak cukup
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <button type="submit" id="btn-submit" class="btn btn-success btn-lg w-100">
                                        Selesai & Simpan Transaksi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.onload = function() {
            hitungTotal();
        };

        let keranjang = [];
        
        function validateQuantity() {
            const qtyInput = document.getElementById('qty-produk');
            const selectedOption = document.getElementById('select-produk').selectedOptions[0];
            
            if (selectedOption) {
                const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
                if (parseInt(qtyInput.value) > stock) {
                    qtyInput.value = stock;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stok Tidak Cukup',
                        text: `Jumlah melebihi stok yang tersedia (${stock})`
                    });
                }
            }
            
            if (parseInt(qtyInput.value) < 1) {
                qtyInput.value = 1;
            }
        }

        function getHargaByTipePelanggan(selectedOption, tipePelanggan) {
            if (!selectedOption) return 0;
            
            let harga = 0;
            switch (tipePelanggan) {
                case '1':
                    harga = parseFloat(selectedOption.getAttribute('data-harga-jual-1')) || 0;
                    break;
                case '2':
                    harga = parseFloat(selectedOption.getAttribute('data-harga-jual-2')) || 0;
                    break;
                case '3':
                    harga = parseFloat(selectedOption.getAttribute('data-harga-jual-3')) || 0;
                    break;
                default:
                    harga = parseFloat(selectedOption.getAttribute('data-harga-jual-1')) || 0;
            }
            return harga;
        }

        function tambahProduk() {
            const pelangganSelect = document.getElementById('pelanggan');
            const selectProduk = document.getElementById('select-produk');
            const qtyProduk = document.getElementById('qty-produk');
            const produkId = selectProduk.value;

            if (!produkId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih produk',
                    text: 'Silakan pilih produk terlebih dahulu.'
                });
                return;
            }

            const qtyInput = parseInt(qtyProduk.value) || 0;
            if (qtyInput < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Quantity Salah',
                    text: 'Jumlah minimal yang harus dipilih adalah 1.'
                });
                return;
            }

            const selectedOption = selectProduk.options[selectProduk.selectedIndex];
            const namaProduk = selectedOption.text;
            const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;

            if (qtyInput > stock) {
                Swal.fire({
                    icon: 'error',
                    title: 'Stok Tidak Cukup',
                    text: `Stok tersedia hanya ${stock} item`
                });
                return;
            }

            const tipePelanggan = pelangganSelect.selectedOptions[0]?.dataset?.tipe || '1';
            const harga = getHargaByTipePelanggan(selectedOption, tipePelanggan);
            const subtotal = harga * qtyInput;

            const existingItemIndex = keranjang.findIndex(item => item.id === produkId);
            
            if (existingItemIndex !== -1) {
                keranjang[existingItemIndex].jumlah += qtyInput;
                keranjang[existingItemIndex].subtotal = keranjang[existingItemIndex].harga * keranjang[existingItemIndex].jumlah;
            } else {
                keranjang.push({
                    id: produkId,
                    nama: namaProduk,
                    harga: harga,
                    jumlah: qtyInput,
                    subtotal: subtotal
                });
            }

            selectProduk.value = '';
            qtyProduk.value = 1;

            renderKeranjang();
            hitungTotal();
        }

        function renderKeranjang() {
            const tbody = document.querySelector('#keranjang-table tbody');
            tbody.innerHTML = '';

            const pelangganSelect = document.getElementById('pelanggan');
            const tipePelanggan = pelangganSelect.selectedOptions[0]?.dataset?.tipe || '1';

            keranjang.forEach((item, index) => {
                const tr = document.createElement('tr');
                
                const selectedOption = document.querySelector(`#select-produk option[value="${item.id}"]`);
                
                item.harga = getHargaByTipePelanggan(selectedOption, tipePelanggan);
                item.subtotal = item.harga * item.jumlah;

                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${item.nama}</td>
                    <td>${item.harga.toLocaleString('id-ID')}</td>
                    <td>${item.jumlah}</td>
                    <td>${item.subtotal.toLocaleString('id-ID')}</td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="hapusItem(${index})">Hapus</button></td>
                `;

                tbody.appendChild(tr);
            });

            syncHiddenInputs();
        }

        function hapusItem(index) {
            keranjang.splice(index, 1);
            renderKeranjang();
            hitungTotal();
        }

        function syncHiddenInputs() {
            document.querySelectorAll('.hidden-item').forEach(el => el.remove());
            const form = document.getElementById('form-transaksi');
            
            keranjang.forEach(item => {
                let inputBarangId = document.createElement('input');
                inputBarangId.type = 'hidden';
                inputBarangId.name = 'barang_id[]';
                inputBarangId.value = item.id;
                inputBarangId.classList.add('hidden-item');
                form.appendChild(inputBarangId);
                
                let inputJumlah = document.createElement('input');
                inputJumlah.type = 'hidden';
                inputJumlah.name = 'jumlah[]';
                inputJumlah.value = item.jumlah;
                inputJumlah.classList.add('hidden-item');
                form.appendChild(inputJumlah);
                
                let inputHarga = document.createElement('input');
                inputHarga.type = 'hidden';
                inputHarga.name = 'harga[]';
                inputHarga.value = item.harga;
                inputHarga.classList.add('hidden-item');
                form.appendChild(inputHarga);
            });
        }

        function hitungTotal() {
            let total = 0;
            keranjang.forEach(item => {
                total += item.subtotal;
            });

            const pelanggan = document.getElementById('pelanggan');
            const selectedPelanggan = pelanggan.selectedOptions[0];
            let tipe_pelanggan = selectedPelanggan ? selectedPelanggan.dataset.tipe : '1';
            let membershipOwned = selectedPelanggan ? parseFloat(selectedPelanggan.getAttribute('data-membership_points')) || 0 : 0;
            
            document.getElementById('label-membership-owned').innerText = membershipOwned.toLocaleString('id-ID');
            
            let diskonPelanggan = 0;
            let diskonThreshold = 0;
            let membershipEarned = 0;
            
            if (pelanggan.value !== "") {
                if (tipe_pelanggan === '1' || tipe_pelanggan === '2') {
                    diskonPelanggan = total * 0.05;
                } else if (tipe_pelanggan === '3') {
                    diskonPelanggan = total * 0.10;
                }
                
                if (total > 100000) {
                    diskonThreshold = total * 0.10;
                } else if (total > 50000) {
                    diskonThreshold = total * 0.05;
                }
                
                if (tipe_pelanggan === '1' || tipe_pelanggan === '2') {
                    membershipEarned = Math.floor(total * 0.02);
                }
            }
            
            let totalDiskon = diskonPelanggan + diskonThreshold;
            let totalSetelahDiskon = total - totalDiskon;
            let ppn = totalSetelahDiskon * 0.12;
            let totalAfterPPN = totalSetelahDiskon + ppn;
            
            let redeemedPoints = parseInt(document.getElementById('membership_points').value) || 0;
            const maxAllowedPoints = Math.floor(totalAfterPPN * 0.5);
            
            if (redeemedPoints > membershipOwned) {
                redeemedPoints = membershipOwned;
                document.getElementById('membership_points').value = redeemedPoints;
                document.getElementById('membership-warning').style.display = 'block';
            } else {
                document.getElementById('membership-warning').style.display = 'none';
            }
            
            if (redeemedPoints > maxAllowedPoints) {
                redeemedPoints = maxAllowedPoints;
                document.getElementById('membership_points').value = redeemedPoints;
            }
            
            let grandTotal = totalAfterPPN - redeemedPoints;
            if (grandTotal < 0) grandTotal = 0;
            
            document.getElementById('label-total').innerText = total.toLocaleString('id-ID');
            document.getElementById('label-diskon').innerText = totalDiskon.toLocaleString('id-ID');
            document.getElementById('label-ppn').innerText = ppn.toLocaleString('id-ID');
            document.getElementById('label-total-akhir').innerText = totalAfterPPN.toLocaleString('id-ID');
            document.getElementById('label-membership-used').innerText = redeemedPoints.toLocaleString('id-ID');
            document.getElementById('label-membership-earned').innerText = membershipEarned.toLocaleString('id-ID');
            document.getElementById('label-grand-total').innerText = grandTotal.toLocaleString('id-ID');
            
            document.getElementById('payment_amount').setAttribute('min', grandTotal);
            let paymentVal = parseFloat(document.getElementById('payment_amount').value) || 0;
            if (paymentVal < grandTotal) {
                document.getElementById('payment_amount').value = grandTotal;
            }
            
            hitungKembalian();
        }

        function hitungKembalian() {
            let paymentAmount = parseFloat(document.getElementById('payment_amount').value) || 0;
            let calculatedGrandTotal = convertRupiah(document.getElementById('label-grand-total').innerText);
            let kembalian = paymentAmount - calculatedGrandTotal;
            
            if (kembalian < 0) {
                kembalian = 0;
                document.getElementById('payment-warning').style.display = 'block';
            } else {
                document.getElementById('payment-warning').style.display = 'none';
            }
            
            document.getElementById('label-kembalian').innerText = kembalian.toLocaleString('id-ID');
        }

        function useMaxMembership() {
            const pelanggan = document.getElementById('pelanggan');
            if (pelanggan.value === "") {
                document.getElementById('membership_points').value = 0;
                hitungTotal();
                return;
            }
            
            let totalAfterPPNValue = convertRupiah(document.getElementById('label-total-akhir').innerText);
            let maxAllowed = Math.floor(totalAfterPPNValue * 0.5);
            let membershipOwned = parseFloat(pelanggan.selectedOptions[0].dataset.membership_points) || 0;
            
            if (membershipOwned >= maxAllowed) {
                document.getElementById('membership_points').value = maxAllowed;
            } else {
                document.getElementById('membership_points').value = Math.floor(membershipOwned);
            }
            
            hitungTotal();
        }

        function convertRupiah(str) {
            let clean = str.replace(/\./g, '');
            clean = clean.replace(',', '.');
            return parseFloat(clean) || 0;
        }

        document.getElementById('form-transaksi').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (keranjang.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Keranjang Kosong',
                    text: 'Silakan tambahkan produk ke keranjang terlebih dahulu.'
                });
                return;
            }
            
            let paymentAmount = Math.round(parseFloat(document.getElementById('payment_amount').value) || 0);
            let calculatedGrandTotal = Math.round(convertRupiah(document.getElementById('label-grand-total').innerText));
            
            if (paymentAmount < calculatedGrandTotal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Uang tidak cukup',
                    text: 'Total pembayaran minimal adalah Rp. ' + calculatedGrandTotal.toLocaleString('id-ID')
                });
                return;
            }
            
            Swal.fire({
                title: 'Konfirmasi Transaksi',
                text: 'Apakah Anda yakin ingin menyelesaikan transaksi ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Selesaikan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = this;

                    const totalBeforeDiscount = convertRupiah(document.getElementById('label-total').innerText);
                    const totalDiscount = convertRupiah(document.getElementById('label-diskon').innerText);
                    const totalPPN = convertRupiah(document.getElementById('label-ppn').innerText);
                    const totalAfterPPN = convertRupiah(document.getElementById('label-total-akhir').innerText);
                    const poinDigunakan = document.getElementById('membership_points').value;
                    const grandTotal = calculatedGrandTotal;
                    const uangDibayar = paymentAmount;
                    const kembalian = convertRupiah(document.getElementById('label-kembalian').innerText);

                    const hiddenFields = {
                        'user_id': '{{ auth()->user()->id }}',
                        'pelanggan_id': document.getElementById('pelanggan').value || null,
                        'tipe_pelanggan': document.getElementById('pelanggan').selectedOptions[0]?.dataset?.tipe || '1',
                        'total_pembelanjaan': totalBeforeDiscount,
                        'diskon_persen': (totalDiscount / totalBeforeDiscount * 100).toFixed(2),
                        'diskon_nominal': totalDiscount,
                        'poin_digunakan': poinDigunakan,
                        'total_setelah_diskon': totalAfterPPN - totalPPN,
                        'ppn': totalPPN,
                        'total_akhir': totalAfterPPN,
                        'uang_dibayar': uangDibayar,
                        'kembalian': kembalian,
                        'poin_didapat': convertRupiah(document.getElementById('label-membership-earned').innerText)
                    };

                    Object.entries(hiddenFields).forEach(([key, value]) => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = value;
                        form.appendChild(input);
                    });

                    form.submit();
                }
            });
        });

        document.getElementById('pelanggan').addEventListener('change', function() {
            const tipePelanggan = this.selectedOptions[0]?.dataset?.tipe || '1';
            
            keranjang.forEach(item => {
                const selectedOption = document.querySelector(`#select-produk option[value="${item.id}"]`);
                if (selectedOption) {
                    item.harga = getHargaByTipePelanggan(selectedOption, tipePelanggan);
                    item.subtotal = item.harga * item.jumlah;
                }
            });

            renderKeranjang();
            hitungTotal();
        });
    </script>
</body>
</html>