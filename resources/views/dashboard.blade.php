<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Lancar Jaya (Sedot WC)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        /* PERBAIKAN: Gunakan !important agar CSS Tailwind tidak menimpa logic kita */
        .hidden-section { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 font-sans">

    <script>
        // Pastikan port sesuai dengan 'php artisan serve' (biasanya 8000)
        const API_URL = "http://127.0.0.1:8000/api"; 
    </script>

    <div id="login-section" class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-96">
            <h2 class="text-2xl font-bold mb-6 text-blue-600 text-center">Login Staff</h2>
            <form onsubmit="handleLogin(event)">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" id="email" class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500" placeholder="admin@lancarjaya.com" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500" placeholder="password123" required>
                </div>
                <button type="submit" id="btn-login" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition">
                    Masuk Dashboard
                </button>
            </form>
            <div id="login-loading" class="text-center mt-4 hidden text-gray-500 text-sm">Sedang memproses...</div>
        </div>
    </div>

    <div id="dashboard-section" class="hidden-section min-h-screen">
        
        <nav class="bg-blue-800 text-white p-4 shadow-md flex justify-between items-center">
            <h1 class="text-xl font-bold">Admin Lancar Jaya</h1>
            <div>
                <span id="user-name" class="mr-4 text-sm opacity-80">Halo, Admin</span>
                <button onclick="handleLogout()" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-sm">Logout</button>
            </div>
        </nav>

        <div class="container mx-auto p-6 flex gap-6">
            
            <aside class="w-1/4 bg-white p-4 rounded shadow h-fit">
                <ul class="space-y-2">
                    <li>
                        <button onclick="showPage('services')" class="w-full text-left p-2 hover:bg-blue-50 rounded font-semibold text-blue-800 border-b">
                            🛠️ Manajemen Layanan
                        </button>
                    </li>
                    <li>
                        <button onclick="showPage('bookings')" class="w-full text-left p-2 hover:bg-blue-50 rounded font-semibold text-green-800 border-b">
                            📅 Data Booking
                        </button>
                    </li>
                </ul>
            </aside>

            <main class="w-3/4">
                
                <div id="page-services" class="bg-white p-6 rounded shadow">
                    <h2 class="text-2xl font-bold mb-4 border-b pb-2">Daftar Layanan (Master)</h2>
                    
                    <div class="mb-6 bg-gray-50 p-4 rounded border">
                        <h3 class="font-bold mb-2 text-sm text-gray-600">Tambah Layanan Baru</h3>
                        <form onsubmit="createService(event)" class="flex gap-2 items-end">
                            <div class="flex-1">
                                <input type="text" id="svc-name" placeholder="Nama Layanan" class="w-full border p-2 rounded text-sm" required>
                            </div>
                            <div class="w-32">
                                <input type="number" id="svc-price" placeholder="Harga" class="w-full border p-2 rounded text-sm" required>
                            </div>
                            <div class="flex-1">
                                <input type="text" id="svc-desc" placeholder="Deskripsi" class="w-full border p-2 rounded text-sm">
                            </div>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Simpan</button>
                        </form>
                    </div>

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 text-sm">
                                <th class="p-3 border-b">Nama Layanan</th>
                                <th class="p-3 border-b">Harga</th>
                                <th class="p-3 border-b">Deskripsi</th>
                                <th class="p-3 border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="services-table-body" class="text-sm">
                            </tbody>
                    </table>
                </div>

                <div id="page-bookings" class="hidden-section bg-white p-6 rounded shadow">
                    <h2 class="text-2xl font-bold mb-4 border-b pb-2">Data Booking (Transaksi)</h2>
                    
                    <div class="mb-6 bg-gray-50 p-4 rounded border">
                        <h3 class="font-bold mb-2 text-sm text-gray-600">Buat Booking Baru</h3>
                        <form onsubmit="createBooking(event)" class="grid grid-cols-2 gap-3">
                            <input type="text" id="bk-name" placeholder="Nama Pelanggan" class="border p-2 rounded text-sm" required>
                            <input type="text" id="bk-phone" placeholder="No HP" class="border p-2 rounded text-sm" required>
                            <input type="text" id="bk-address" placeholder="Alamat Lengkap" class="border p-2 rounded text-sm col-span-2" required>
                            <input type="date" id="bk-date" class="border p-2 rounded text-sm" required>
                            
                            <select id="bk-service-id" class="border p-2 rounded text-sm bg-white" required>
                                <option value="">Pilih Layanan...</option>
                            </select>

                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700 col-span-2">Buat Booking</button>
                        </form>
                    </div>

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 text-sm">
                                <th class="p-3 border-b">Tgl</th>
                                <th class="p-3 border-b">Pelanggan</th>
                                <th class="p-3 border-b">Layanan</th>
                                <th class="p-3 border-b">Status</th>
                                <th class="p-3 border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bookings-table-body" class="text-sm">
                            </tbody>
                    </table>
                </div>

            </main>
        </div>
    </div>

    <script>
        // --- 1. AUTH MANAGEMENT ---
        
        function getToken() {
            return localStorage.getItem('access_token');
        }

        // Cek status login saat halaman dibuka
        document.addEventListener('DOMContentLoaded', () => {
            const token = getToken();
            if (token) {
                showDashboard();
            } else {
                document.getElementById('login-section').classList.remove('hidden-section');
            }
        });

        // --- LOGIN FUNCTION (FIXED & DEBUGGED) ---
        async function handleLogin(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const btn = document.getElementById('btn-login');
            const loading = document.getElementById('login-loading');

            // UI Feedback
            btn.disabled = true;
            btn.innerText = "Loading...";
            loading.classList.remove('hidden');

            try {
                // Panggil API Login
                const response = await axios.post(`${API_URL}/auth/login`, { email, password });
                
                // Simpan Token JWT
                if(response.data.access_token) {
                    localStorage.setItem('access_token', response.data.access_token);
                    alert("Login Berhasil! Selamat datang.");
                    showDashboard();
                } else {
                    alert("Login gagal: Token tidak diterima.");
                }
                
            } catch (error) {
                console.error("Login Error:", error);
                
                // Tampilkan pesan error spesifik
                if (error.response) {
                    if (error.response.status === 401) {
                        alert("Gagal: Email atau Password Salah!");
                    } else if (error.response.status === 500) {
                        alert("Gagal: Error Server/Database. Pastikan database aktif.");
                    } else {
                        alert(`Gagal: ${error.response.statusText} (${error.response.status})`);
                    }
                } else if (error.request) {
                    alert("Gagal: Tidak ada respon dari server. Pastikan 'php artisan serve' jalan!");
                } else {
                    alert("Error Script: " + error.message);
                }
            } finally {
                // Reset UI
                btn.disabled = false;
                btn.innerText = "Masuk Dashboard";
                loading.classList.add('hidden');
            }
        }

        function handleLogout() {
            if(confirm("Yakin ingin logout?")) {
                localStorage.removeItem('access_token');
                location.reload();
            }
        }

        function showDashboard() {
            // Sembunyikan login dan tampilkan dashboard
            document.getElementById('login-section').classList.add('hidden-section');
            document.getElementById('dashboard-section').classList.remove('hidden-section');
            
            // Ambil nama user
            axios.get(`${API_URL}/auth/me`, {
                headers: { Authorization: `Bearer ${getToken()}` }
            }).then(res => {
                document.getElementById('user-name').innerText = `Halo, ${res.data.name}`;
            }).catch(e => {
                console.log("Token expired or invalid");
                // handleLogout(); 
            });

            loadServices(); // Default page
        }

        function showPage(page) {
            document.getElementById('page-services').classList.add('hidden-section');
            document.getElementById('page-bookings').classList.add('hidden-section');
            document.getElementById(`page-${page}`).classList.remove('hidden-section');
            
            if(page === 'services') loadServices();
            if(page === 'bookings') loadBookings();
        }

        // --- 2. SERVICES CRUD ---

        async function loadServices() {
            try {
                const response = await axios.get(`${API_URL}/services`, {
                    headers: { Authorization: `Bearer ${getToken()}` }
                });
                
                const tbody = document.getElementById('services-table-body');
                const select = document.getElementById('bk-service-id'); 
                
                tbody.innerHTML = '';
                select.innerHTML = '<option value="">Pilih Layanan...</option>';

                if(response.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="p-3 text-center text-gray-500">Belum ada data layanan.</td></tr>';
                }

                response.data.forEach(service => {
                    tbody.innerHTML += `
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">${service.name}</td>
                            <td class="p-3">Rp ${new Intl.NumberFormat('id-ID').format(service.price)}</td>
                            <td class="p-3 text-gray-500">${service.description || '-'}</td>
                            <td class="p-3">
                                <button onclick="deleteService('${service.uuid}')" class="text-red-500 hover:text-red-700 font-bold text-sm">Hapus</button>
                            </td>
                        </tr>
                    `;
                    // Isi dropdown booking
                    select.innerHTML += `<option value="${service.id}">${service.name} - Rp ${service.price}</option>`;
                });
            } catch (error) {
                console.error("Gagal load services", error);
                if(error.response && error.response.status === 401) {
                    alert("Sesi habis. Silakan login ulang.");
                    handleLogout();
                }
            }
        }

        async function createService(e) {
            e.preventDefault();
            const data = {
                name: document.getElementById('svc-name').value,
                price: document.getElementById('svc-price').value,
                description: document.getElementById('svc-desc').value,
            };

            try {
                await axios.post(`${API_URL}/services`, data, {
                    headers: { Authorization: `Bearer ${getToken()}` }
                });
                e.target.reset();
                loadServices();
                alert("Sukses! Layanan berhasil ditambahkan.");
            } catch (error) {
                console.error(error);
                alert("Gagal menambah layanan. Cek inputan.");
            }
        }

        async function deleteService(uuid) {
            if(!confirm("Yakin hapus layanan ini?")) return;
            try {
                await axios.delete(`${API_URL}/services/${uuid}`, {
                    headers: { Authorization: `Bearer ${getToken()}` }
                });
                loadServices();
            } catch (error) {
                alert("Gagal menghapus layanan.");
            }
        }

        // --- 3. BOOKINGS CRUD ---

        async function loadBookings() {
            loadServices(); // Refresh dropdown relasi

            try {
                const response = await axios.get(`${API_URL}/bookings`, {
                    headers: { Authorization: `Bearer ${getToken()}` }
                });

                const tbody = document.getElementById('bookings-table-body');
                tbody.innerHTML = '';

                // Handle pagination format (Laravel paginate return .data)
                const bookings = response.data.data ? response.data.data : response.data;

                if(bookings.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="p-3 text-center text-gray-500">Belum ada booking masuk.</td></tr>';
                }

                bookings.forEach(booking => {
                    const serviceName = booking.service ? booking.service.name : '<span class="text-red-400 italic">Layanan Dihapus</span>';
                    
                    tbody.innerHTML += `
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 text-gray-600">${booking.booking_date}</td>
                            <td class="p-3 font-medium">
                                ${booking.customer_name}<br>
                                <span class="text-xs text-gray-500">${booking.customer_phone}</span>
                            </td>
                            <td class="p-3 text-blue-600 font-semibold">${serviceName}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-xs font-bold bg-yellow-100 text-yellow-700">${booking.status.toUpperCase()}</span>
                            </td>
                            <td class="p-3">
                                <button onclick="deleteBooking('${booking.uuid}')" class="text-red-500 hover:text-red-700 font-bold text-sm">Batal</button>
                            </td>
                        </tr>
                    `;
                });
            } catch (error) {
                console.error("Gagal load bookings", error);
            }
        }

        async function createBooking(e) {
            e.preventDefault();
            const data = {
                customer_name: document.getElementById('bk-name').value,
                customer_phone: document.getElementById('bk-phone').value,
                address: document.getElementById('bk-address').value,
                booking_date: document.getElementById('bk-date').value,
                service_id: document.getElementById('bk-service-id').value,
            };

            try {
                await axios.post(`${API_URL}/bookings`, data, {
                    headers: { Authorization: `Bearer ${getToken()}` }
                });
                e.target.reset();
                loadBookings();
                alert("Sukses! Booking berhasil dibuat.");
            } catch (error) {
                console.error(error);
                alert("Gagal booking. Pastikan semua field terisi.");
            }
        }

        async function deleteBooking(uuid) {
            if(!confirm("Yakin hapus data booking ini?")) return;
            try {
                await axios.delete(`${API_URL}/bookings/${uuid}`, {
                    headers: { Authorization: `Bearer ${getToken()}` }
                });
                loadBookings();
            } catch (error) {
                alert("Gagal menghapus booking.");
            }
        }

    </script>
</body>
</html>