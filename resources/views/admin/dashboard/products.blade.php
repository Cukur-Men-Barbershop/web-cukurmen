@extends('admin.admindashboard')

@section('content')
    <div class="main-tab-content active" id="produk-tab-content">
        <div class="content-card">
            <h1 class="dashboard-header">Kelola Daftar <span>Produk</span> & Layanan</h1>
            <p style="color: var(--text-grey); margin-bottom: 2rem;">Di sini Anda dapat melihat, menambah, mengedit, dan
                menghapus daftar layanan potong rambut, perawatan, dan produk ritel yang ditawarkan barbershop.</p>

            <!-- Form untuk tambah layanan -->
            <div class="form-section" style="margin-bottom: 2rem; padding: 1.5rem; background: var(--surface-light);">
                <h4 style="font-size: 1.2rem; color: var(--accent-gold); margin-bottom: 1rem;">Tambah Layanan Baru</h4>
                <form id="addServiceForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Layanan</label>
                            <input type="text" id="serviceName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Harga (Rp)</label>
                            <input type="number" id="servicePrice" name="price" required>
                        </div>
                        <div class="form-group">
                            <label>Durasi (menit)</label>
                            <input type="number" id="serviceDuration" name="duration" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea id="serviceDescription" name="description" style="height: 80px;"></textarea>
                    </div>
                    <button type="submit" class="btn-primary" style="margin-top: 1rem;">Tambah Layanan</button>
                </form>
            </div>

            <h4
                style="font-size: 1.5rem; border-top: var(--border-subtle); padding-top: 2rem; margin-top: 2rem; color: var(--accent-gold);">
                Daftar Layanan Cukur</h4>
            <div id="layananCukurList">
                @if (isset($services) && $services->count() > 0)
                    @foreach ($services as $service)
                        <div class="service-item" data-id="{{ $service->id }}">
                            <div class="service-info">
                                <h4>{{ $service->name }}</h4>
                                <p>{{ $service->description ?? '' }}</p>
                                <span class="service-price">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                                @if ($service->duration)
                                    <p>Durasi: {{ $service->duration }} menit</p>
                                @endif
                            </div>
                            <div class="service-actions">
                                <button class="btn-small btn-edit" onclick="editService({{ $service->id }})">Edit</button>
                                <button class="btn-small btn-delete"
                                    onclick="deleteService({{ $service->id }})">Hapus</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p style="color: var(--text-grey); text-align: center; padding: 2rem;">Tidak ada layanan cukur</p>
                @endif
            </div>

            <!-- Form untuk tambah barber -->
            <div class="form-section"
                style="margin-bottom: 2rem; padding: 1.5rem; background: var(--surface-light); border-top: var(--border-subtle); margin-top: 2rem; padding-top: 2rem;">
                <h4 style="font-size: 1.2rem; color: var(--accent-gold); margin-bottom: 1rem;">Tambah Barber Baru</h4>
                <form id="addBarberForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Barber</label>
                            <input type="text" id="barberName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Specialty</label>
                            <input type="text" id="barberSpecialty" name="specialty">
                        </div>
                        <div class="form-group">
                            <label>Rating</label>
                            <input type="number" id="barberRating" name="rating" min="0" max="5"
                                step="0.1">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select id="barberStatus" name="status" required>
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Upload Gambar</label>
                        <input type="file" id="barberImage" name="image" accept="image/*"
                            placeholder="Pilih gambar barber">
                    </div>
                    <button type="submit" class="btn-primary" style="margin-top: 1rem;">Tambah Barber</button>
                </form>
            </div>

            <h4
                style="font-size: 1.5rem; border-top: var(--border-subtle); padding-top: 2rem; margin-top: 2rem; color: var(--accent-gold);">
                Daftar Barber</h4>
            <div id="barberList">
                <!-- Barber list will be populated here -->
            </div>

            <!-- Form untuk tambah produk -->
            <div class="form-section"
                style="margin-bottom: 2rem; padding: 1.5rem; background: var(--surface-light); border-top: var(--border-subtle); margin-top: 2rem; padding-top: 2rem;">
                <h4 style="font-size: 1.2rem; color: var(--accent-gold); margin-bottom: 1rem;">Tambah Produk Baru</h4>
                <form id="addProductForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Produk</label>
                            <input type="text" id="productName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Harga (Rp)</label>
                            <input type="number" id="productPrice" name="price" required>
                        </div>
                        <div class="form-group">
                            <label>Stok</label>
                            <input type="number" id="productStock" name="stock_quantity" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select id="productStatus" name="status" required>
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea id="productDescription" name="description" style="height: 80px;"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Upload Gambar</label>
                        <input type="file" id="productImage" name="image" accept="image/*"
                            placeholder="Pilih gambar produk">
                    </div>
                    <button type="submit" class="btn-primary" style="margin-top: 1rem;">Tambah Produk</button>
                </form>
            </div>

            <h4
                style="font-size: 1.5rem; border-top: var(--border-subtle); padding-top: 2rem; margin-top: 2rem; color: var(--accent-gold);">
                Produk Ritel (Hanya Beli di Tempat)</h4>
            <div class="admin-product-grid" id="produkRitelGrid">
                @if (isset($products) && $products->count() > 0)
                    @foreach ($products as $product)
                        <div class="product-card" data-id="{{ $product->id }}">
                            <img class="product-card-img"
                                src="{{ $product->image_path ?? ($product->image ?? ($product->img ?? '/assets/img/default-product.jpg')) }}"
                                alt="{{ $product->name ?? 'Product' }}">
                            <h3>{{ $product->name ?? 'N/A' }}</h3>
                            <span class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <span
                                class="stock-status {{ $product->status == 'active' || $product->stok ? 'ready' : 'out-of-stock' }}">{{ $product->status ?? ($product->stok ?? 'Ready Stock') }}</span>
                            <div class="product-actions">
                                <button class="btn-small btn-edit"
                                    onclick="editProduct({{ $product->id }})">Edit</button>
                                <button class="btn-small btn-delete"
                                    onclick="deleteProduct({{ $product->id }})">Hapus</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p style="color: var(--text-grey); text-align: center; padding: 2rem;">Tidak ada produk ritel</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal untuk edit layanan -->
    <div id="editServiceModal" class="modal"
        style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4);">
        <div class="modal-content"
            style="background-color: var(--surface-card); margin: 5% auto; padding: 20px; border: none; border-radius: 8px; width: 60%; max-width: 600px;">
            <h3 style="color: var(--accent-gold);">Edit Layanan</h3>
            <form id="editServiceForm">
                <input type="hidden" id="editServiceId" name="id">
                <div class="form-group">
                    <label>Nama Layanan</label>
                    <input type="text" id="editServiceName" name="name" required>
                </div>
                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" id="editServicePrice" name="price" required>
                </div>
                <div class="form-group">
                    <label>Durasi (menit)</label>
                    <input type="number" id="editServiceDuration" name="duration" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea id="editServiceDescription" name="description" style="height: 80px;"></textarea>
                </div>
                <div style="margin-top: 1rem;">
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    <button type="button" class="btn-secondary" onclick="closeEditServiceModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal untuk edit produk -->
    <div id="editProductModal" class="modal"
        style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4);">
        <div class="modal-content"
            style="background-color: var(--surface-card); margin: 5% auto; padding: 20px; border: none; border-radius: 8px; width: 60%; max-width: 600px;">
            <h3 style="color: var(--accent-gold);">Edit Produk</h3>
            <form id="editProductForm">
                <input type="hidden" id="editProductId" name="id">
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" id="editProductName" name="name" required>
                </div>
                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" id="editProductPrice" name="price" required>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" id="editProductStock" name="stock_quantity" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select id="editProductStatus" name="status" required>
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea id="editProductDescription" name="description" style="height: 80px;"></textarea>
                </div>
                <div class="form-group">
                    <label>Upload Gambar</label>
                    <input type="file" id="editProductImage" name="image" accept="image/*"
                        placeholder="Pilih gambar produk">
                    <small>Biarkan kosong jika tidak ingin mengganti gambar</small>
                </div>
                <div style="margin-top: 1rem;">
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    <button type="button" class="btn-secondary" onclick="closeEditProductModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fungsi untuk menangani form tambah layanan
        document.getElementById('addServiceForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            // Tambahkan CSRF token ke formData jika belum ada
            if (!formData.get('_token')) {
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content'));
            }

            fetch('{{ route('admin.services.create') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        alert('Layanan berhasil ditambahkan!');
                        location.reload(); // Refresh halaman untuk menampilkan data baru
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menambahkan layanan');
                });
        });

        // Fungsi untuk menangani form tambah produk
        document.getElementById('addProductForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            // Tambahkan CSRF token ke formData jika belum ada
            if (!formData.get('_token')) {
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content'));
            }

            fetch('{{ route('admin.products.create') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        // Jangan set Content-Type header saat menggunakan FormData - browser akan mengaturnya sendiri
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        alert('Produk berhasil ditambahkan!');
                        location.reload(); // Refresh halaman untuk menampilkan data baru
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menambahkan produk');
                });
        });

        // Fungsi untuk edit layanan
        function editService(id) {
            // Ambil data layanan dari server
            fetch('{{ route('admin.services.show', ['id' => 'ID_PLACEHOLDER']) }}'.replace('ID_PLACEHOLDER', id))
                .then(response => response.json())
                .then(service => {
                    document.getElementById('editServiceId').value = service.id;
                    document.getElementById('editServiceName').value = service.name;
                    document.getElementById('editServicePrice').value = service.price;
                    document.getElementById('editServiceDuration').value = service.duration;
                    document.getElementById('editServiceDescription').value = service.description || '';

                    document.getElementById('editServiceModal').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data layanan');
                });
        }

        // Fungsi untuk edit produk
        function editProduct(id) {
            // Ambil data produk dari server
            fetch('{{ route('admin.products.show', ['id' => 'ID_PLACEHOLDER']) }}'.replace('ID_PLACEHOLDER', id))
                .then(response => response.json())
                .then(product => {
                    document.getElementById('editProductId').value = product.id;
                    document.getElementById('editProductName').value = product.name;
                    document.getElementById('editProductPrice').value = product.price;
                    document.getElementById('editProductStock').value = product.stock_quantity;
                    document.getElementById('editProductStatus').value = product.status;
                    document.getElementById('editProductDescription').value = product.description || '';
                    // Don't set the file input field, but clear it so the user knows to select a new file if they want to change
                    document.getElementById('editProductImage').value = '';

                    document.getElementById('editProductModal').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data produk');
                });
        }

        // Fungsi untuk menutup modal edit layanan
        function closeEditServiceModal() {
            document.getElementById('editServiceModal').style.display = 'none';
            // Reset form setelah ditutup
            document.getElementById('editServiceForm').reset();
        }

        // Fungsi untuk menutup modal edit produk
        function closeEditProductModal() {
            document.getElementById('editProductModal').style.display = 'none';
            // Reset form setelah ditutup
            document.getElementById('editProductForm').reset();
        }

        // Fungsi untuk menangani form edit layanan
        document.getElementById('editServiceForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const id = document.getElementById('editServiceId').value;
            const formData = new FormData(this);

            formData.append('_method', 'PUT');

            // Add debugging
            console.log('Updating service with ID:', id);
            console.log('Form data keys:', Array.from(formData.keys()));

            fetch('{{ route('admin.services.update', ['id' => 'ID_PLACEHOLDER']) }}'.replace('ID_PLACEHOLDER',
                    id), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.message || (data.id && data.name)) {
                        alert('Layanan berhasil diperbarui!');
                        closeEditServiceModal(); // Close modal instead of reload for better UX
                        location.reload(); // Refresh halaman untuk menampilkan perubahan
                    } else if (data.errors) {
                        let errorMsg = 'Error validasi:\n';
                        for (let field in data.errors) {
                            errorMsg += field + ': ' + data.errors[field].join(', ') + '\n';
                        }
                        alert(errorMsg);
                    } else {
                        alert('Terjadi kesalahan saat memperbarui layanan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui layanan: ' + error.message);
                });
        });

        // Fungsi untuk menangani form edit produk
        document.getElementById('editProductForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const id = document.getElementById('editProductId').value;
            const formData = new FormData(this);

            formData.append('_method', 'PUT');

            // Add debugging
            console.log('Updating product with ID:', id);
            console.log('Form data keys:', Array.from(formData.keys()));

            fetch('{{ route('admin.products.update', ['id' => 'ID_PLACEHOLDER']) }}'.replace('ID_PLACEHOLDER',
                    id), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        // Jangan set Content-Type header saat menggunakan FormData - browser akan mengaturnya sendiri
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.message || (data.id && data.name)) {
                        alert('Produk berhasil diperbarui!');
                        closeEditProductModal(); // Close modal instead of reload for better UX
                        location.reload(); // Refresh halaman untuk menampilkan perubahan
                    } else if (data.errors) {
                        let errorMsg = 'Error validasi:\n';
                        for (let field in data.errors) {
                            errorMsg += field + ': ' + data.errors[field].join(', ') + '\n';
                        }
                        alert(errorMsg);
                    } else {
                        alert('Terjadi kesalahan saat memperbarui produk');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui produk: ' + error.message);
                });
        });

        // Fungsi untuk hapus layanan
        function deleteService(id) {
            if (confirm('Apakah Anda yakin ingin menghapus layanan ini?')) {
                fetch('{{ route('admin.services.delete', ['id' => 'ID_PLACEHOLDER']) }}'.replace('ID_PLACEHOLDER', id), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            alert('Layanan berhasil dihapus!');
                            location.reload(); // Refresh halaman untuk menampilkan perubahan
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus layanan');
                    });
            }
        }

        // Fungsi untuk hapus produk
        function deleteProduct(id) {
            if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                fetch('{{ route('admin.products.delete', ['id' => 'ID_PLACEHOLDER']) }}'.replace('ID_PLACEHOLDER', id), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            alert('Produk berhasil dihapus!');
                            location.reload(); // Refresh halaman untuk menampilkan perubahan
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus produk');
                    });
            }
        }

        // Fetch and display barber list
        function loadBarberList() {
            fetch('{{ route('admin.barbers') }}')
                .then(response => response.json())
                .then(barbers => {
                    const barberListContainer = document.getElementById('barberList');
                    if (barbers.length === 0) {
                        barberListContainer.innerHTML =
                            '<p style="color: var(--text-grey); text-align: center; padding: 2rem;">Tidak ada barber</p>';
                        return;
                    }

                    let barberHTML = '';
                    barbers.forEach(barber => {
                        const barberImage = barber.image_path ||
                            '/assets/img/barber1.jpg'; // Use default if no image
                        barberHTML += `
                <div class="barber-item" data-id="${barber.id}" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border-bottom: 1px solid var(--border-subtle);">
                    <div class="barber-info" style="display: flex; align-items: center; gap: 10px;">
                        <img src="${barberImage}" alt="${barber.name}" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;" onerror="this.onerror=null; this.src='/assets/img/barber1.jpg';">
                        <div>
                            <h4>${barber.name}</h4>
                            <p>${barber.specialty || ''}</p>
                            <div style="display: flex; gap: 10px; margin-top: 5px;">
                                <span>Rating: ${barber.rating || 'N/A'}</span>
                                <span>Status: <span class="status-badge ${barber.status}">${barber.status}</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="barber-actions" style="display: flex; gap: 5px;">
                        <button class="btn-small btn-edit" onclick="editBarber(${barber.id})">Edit</button>
                        <button class="btn-small" style="background-color: ${barber.status === 'active' ? 'var(--button-warning)' : 'var(--button-success)'};"
                            onclick="toggleBarberStatus(${barber.id}, '${barber.status}')"
                            title="${barber.status === 'active' ? 'Nonaktifkan' : 'Aktifkan'}">
                            ${barber.status === 'active' ? 'Nonaktifkan' : 'Aktifkan'}
                        </button>
                        <button class="btn-small btn-delete" onclick="deleteBarber(${barber.id})">Hapus</button>
                    </div>
                </div>
            `;
                    });
                    barberListContainer.innerHTML = barberHTML;
                })
                .catch(error => {
                    console.error('Error loading barbers:', error);
                    alert('Terjadi kesalahan saat memuat daftar barber');
                });
        }

        // Fungsi untuk menangani form tambah barber
        document.getElementById('addBarberForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            // Tambahkan CSRF token ke formData jika belum ada
            if (!formData.get('_token')) {
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content'));
            }

            fetch('{{ route('admin.barbers.create') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        // Jangan set Content-Type header saat menggunakan FormData - browser akan mengaturnya sendiri
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        alert('Barber berhasil ditambahkan!');
                        document.getElementById('addBarberForm').reset();
                        loadBarberList(); // Reload the barber list
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menambahkan barber');
                });
        });

        // Fungsi untuk edit barber
        function editBarber(id) {
            // Ambil data barber dari server
            fetch('{{ route('admin.barbers.show', ['id' => 'ID_PLACEHOLDER']) }}'.replace('ID_PLACEHOLDER', id))
                .then(response => response.json())
                .then(barber => {
                    document.getElementById('editBarberId').value = barber.id;
                    document.getElementById('editBarberName').value = barber.name;
                    document.getElementById('editBarberSpecialty').value = barber.specialty || '';
                    document.getElementById('editBarberRating').value = barber.rating || 0;
                    document.getElementById('editBarberStatus').value = barber.status;
                    // Note: We can't set the file input field programmatically, so we'll just clear it
                    document.getElementById('editBarberImage').value = ''; // Don't set a value for file input

                    document.getElementById('editBarberModal').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data barber');
                });
        }

        // Fungsi untuk toggle status barber
        function toggleBarberStatus(id, currentStatus) {
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
            const confirmText = newStatus === 'active' ? 'mengaktifkan' : 'menonaktifkan';

            if (confirm(`Apakah Anda yakin ingin ${confirmText} barber ini?`)) {
                fetch(`{{ route('admin.barbers.update', ['id' => 'ID_PLACEHOLDER']) }}`.replace('ID_PLACEHOLDER', id), {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            status: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            alert(`Status barber berhasil diubah menjadi ${newStatus}!`);
                            loadBarberList(); // Reload the barber list
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengubah status barber');
                    });
            }
        }

        // Fungsi untuk hapus barber
        function deleteBarber(id) {
            if (confirm('Apakah Anda yakin ingin menghapus barber ini?')) {
                fetch('{{ route('admin.barbers.delete', ['id' => 'ID_PLACEHOLDER']) }}'.replace('ID_PLACEHOLDER', id), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            alert('Barber berhasil dihapus!');
                            loadBarberList(); // Reload the barber list
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus barber');
                    });
            }
        }

        // Create the edit barber modal HTML dynamically if it doesn't exist
        if (!document.getElementById('editBarberModal')) {
            const modalHTML = `
        <div id="editBarberModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4);">
            <div class="modal-content" style="background-color: var(--surface-card); margin: 5% auto; padding: 20px; border: none; border-radius: 8px; width: 60%; max-width: 600px;">
                <h3 style="color: var(--accent-gold);">Edit Barber</h3>
                <form id="editBarberForm">
                    <input type="hidden" id="editBarberId" name="id">
                    <div class="form-group">
                        <label>Nama Barber</label>
                        <input type="text" id="editBarberName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Specialty</label>
                        <input type="text" id="editBarberSpecialty" name="specialty">
                    </div>
                    <div class="form-group">
                        <label>Rating</label>
                        <input type="number" id="editBarberRating" name="rating" min="0" max="5" step="0.1">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select id="editBarberStatus" name="status" required>
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Upload Gambar</label>
                        <input type="file" id="editBarberImage" name="image" accept="image/*" placeholder="Pilih gambar barber">
                        <small>Biarkan kosong jika tidak ingin mengganti gambar</small>
                    </div>
                    <div style="margin-top: 1rem;">
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                        <button type="button" class="btn-secondary" onclick="closeEditBarberModal()">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    `;
            document.body.insertAdjacentHTML('beforeend', modalHTML);

            // Setelah modal dibuat, tambahkan event listener untuk form
            document.getElementById('editBarberForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const id = document.getElementById('editBarberId').value;
                const formData = new FormData(this);

                formData.append('_method', 'PUT');

                fetch('{{ route('admin.barbers.update', ['id' => 'ID_PLACEHOLDER']) }}'.replace('ID_PLACEHOLDER',
                        id), {
                        method: 'POST',
                        body: formData,
                        headers: {
                            // Jangan set Content-Type header saat menggunakan FormData - browser akan mengaturnya sendiri
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            alert('Barber berhasil diperbarui!');
                            closeEditBarberModal();
                            loadBarberList(); // Reload the barber list
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memperbarui barber');
                    });
            });
        }

        // Fungsi untuk menutup modal edit barber
        function closeEditBarberModal() {
            document.getElementById('editBarberModal').style.display = 'none';
            // Reset form setelah ditutup
            document.getElementById('editBarberForm').reset();
        }

        // Load barber list when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadBarberList();
        });

        // Tutup modal saat klik di luar konten modal
        window.onclick = function(event) {
            const serviceModal = document.getElementById('editServiceModal');
            const productModal = document.getElementById('editProductModal');
            const barberModal = document.getElementById('editBarberModal'); // Added barber modal

            if (event.target == serviceModal) {
                serviceModal.style.display = 'none';
            }

            if (event.target == productModal) {
                productModal.style.display = 'none';
            }

            if (barberModal && event.target == barberModal) {
                barberModal.style.display = 'none';
            }
        }
    </script>
    <style>
        /* Responsive styles for the products page */
        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .form-group {
            flex: 1;
            min-width: 250px;
            /* Minimum width for desktop */
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }

            .form-group {
                min-width: 100%;
            }

            .content-card {
                padding: 1rem;
            }

            .modal-content {
                width: 90%;
                margin: 10% auto;
                padding: 15px;
            }

            .service-item,
            .barber-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .service-actions,
            .barber-actions {
                width: 100%;
                display: flex;
                justify-content: center;
            }

            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 1rem;
            }

            h1.dashboard-header {
                font-size: 1.5rem;
            }

            h4 {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 480px) {
            .product-grid {
                grid-template-columns: 1fr;
            }

            .modal-content {
                width: 95%;
                margin: 15% auto;
            }
        }

        /* Desktop improvements */
        @media (min-width: 769px) {
            .admin-product-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 1.5rem;
            }

            .product-actions {
                position: absolute;
                bottom: 10px;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                gap: 5px;
                opacity: 0;
                transition: opacity 0.3s;
            }

            .product-card:hover .product-actions {
                opacity: 1;
            }
        }

        /* Product card improvements */
        .product-card {
            position: relative;
            background: var(--bg-secondary);
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .product-card-img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        /* Responsive product grid for mobile */
        @media (max-width: 768px) {
            .admin-product-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 1rem;
            }
        }

        @media (max-width: 480px) {
            .admin-product-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Improve the service and barber item layouts */
        .service-item,
        .barber-item {
            background: var(--bg-secondary);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
        }

        .service-info,
        .barber-info {
            flex: 1;
        }

        .service-actions,
        .barber-actions {
            display: flex;
            gap: 0.5rem;
            align-self: flex-start;
        }

        /* Responsive form inputs */
        input[type="text"],
        input[type="number"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid var(--border-subtle);
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .btn-primary,
        .btn-secondary,
        .btn-small {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            text-align: center;
        }

        .btn-small {
            padding: 0.3rem 0.6rem;
            font-size: 0.8rem;
        }

        .btn-edit {
            background-color: var(--button-warning);
            color: white;
        }

        .btn-delete {
            background-color: var(--button-danger);
            color: white;
        }

        .status-badge.active {
            background-color: var(--button-success);
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            font-size: 0.8rem;
        }

        .status-badge.inactive {
            background-color: var(--button-danger);
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            font-size: 0.8rem;
        }

        /* Enhanced user-friendly styling */
        .service-item,
        .barber-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        input[type="file"] {
            padding: 0.4rem;
        }

        label {
            display: block;
            margin-bottom: 0.3rem;
            font-weight: 500;
            color: var(--text-dark);
        }

        .btn-primary,
        .btn-secondary,
        .btn-small {
            transition: background-color 0.2s, transform 0.2s;
        }

        .btn-primary:hover,
        .btn-secondary:hover,
        .btn-small:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn-primary {
            background-color: var(--accent-gold);
            color: var(--text-dark);
            font-weight: bold;
        }

        .btn-secondary {
            background-color: var(--button-warning);
            color: white;
        }

        /* Form section styling */
        .form-section {
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Modal enhancements */
        .modal-content {
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal {
            animation: fadeIn 0.3s;
        }

        .modal-content {
            animation: slideIn 0.3s;
        }

        /* Service and product list styling */
        .service-price {
            font-weight: bold;
            color: var(--accent-gold);
            font-size: 1.1rem;
        }

        /* Better text styling */
        h1.dashboard-header {
            margin-bottom: 1rem;
        }

        /* Form group enhancements */
        .form-group {
            margin-bottom: 1rem;
        }

        /* Responsive image styling */
        .barber-info img {
            border: 2px solid var(--accent-gold);
        }

        /* Button group styling */
        .btn-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* Add hover effects for better UX */
        .product-card,
        .service-item,
        .barber-item {
            transition: all 0.2s ease;
        }

        /* Input field enhancements */
        input[type="text"],
        input[type="number"],
        input[type="file"],
        select,
        textarea {
            background-color: var(--bg-light);
            color: var(--text-dark);
            transition: border-color 0.2s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 2px rgba(218, 156, 50, 0.2);
        }

        /* Status badge improvements */
        .status-badge.active {
            background-color: var(--button-success);
            color: white;
            padding: 0.25rem 0.6rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-badge.inactive {
            background-color: var(--button-warning);
            color: white;
            padding: 0.25rem 0.6rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }
    </style>

@endsection
