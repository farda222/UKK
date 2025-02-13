<div class="bg-white">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full bg-white shadow-lg rounded-lg p-8 space-y-6">
            <h1 class="text-2xl font-bold text-gray-800">Konfirmasi Penyewaan</h1>

            <div class="space-y-4">
                @php
                    $rental = session('rental');
                @endphp

                <h3>Konfirmasi Penyewaan</h3>

                @if ($rental)
                    <p>Nama Penyewa: {{ $rental['customer_name'] }}</p>
                    <p>Nama Mobil: {{ $rental['car_name'] }}</p>
                    <p>Harga Per Hari: {{ $rental['price_per_day'] }}</p>
                @else
                    <p>Data penyewaan tidak ditemukan.</p>
                @endif

                <form id="rentalForm" action="{{ route('process.rental') }}" method="POST">
                    @csrf
                    <input type="hidden" name="customer_name" value="{{ $rental['customer_name'] }}">
                    <input type="hidden" name="car_name" value="{{ $rental['car_name'] }}">
                    <input type="hidden" name="price_per_day" value="{{ $rental['price_per_day'] }}">
                    <input type="hidden" name="fine" value="{{ $rental['fine'] }}">

                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Tentukan tanggal
                            sewa
                            dan jam pengambilan mobil</label>
                        <input type="datetime-local" name="start_date" id="start_date" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="mt-4">
                        <label for="end_date" class="block text-sm font-medium text-gray-700">Tentukan tanggal sewa
                            dan
                            jam pengembalian mobil
                        </label>
                        <input type="datetime-local" name="end_date" id="end_date" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="mt-4">
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode
                            Pembayaran</label>
                        <select name="payment_method" id="payment_method" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            <option value="">Pilih metode pembayaran</option>
                            <option value="cash">Cash</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>

                    <!-- Informasi rekening bank (hanya muncul jika memilih transfer) -->
                    <div id="bankInfo" class="mt-4 hidden">
                        <div class="p-4 bg-gray-50 rounded-md">
                            <h4 class="font-medium text-gray-900">Informasi Rekening:</h4>
                            <p class="mt-2 text-gray-600">Bank BCA: 1234567890</p>
                            <p class="text-gray-600">A/N: Nama Pemilik</p>
                        </div>
                    </div>

                    <button type="button" onclick="handleSubmit()"
                        class="w-full mt-6 py-3 px-6 bg-blue-500 text-white font-semibold rounded-md shadow-lg hover:bg-blue-600">
                        Konfirmasi Sewa
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Tampilkan/sembunyikan informasi rekening bank
        document.getElementById('payment_method').addEventListener('change', function() {
            const bankInfo = document.getElementById('bankInfo');
            bankInfo.classList.toggle('hidden', this.value !== 'transfer');
        });

        async function handleSubmit() {
            const form = document.getElementById('rentalForm');
            const formData = new FormData(form);

            const customerName = formData.get('customer_name');
            const carName = formData.get('car_name');
            const startDate = formData.get('start_date');
            const endDate = formData.get('end_date');
            const pricePerDay = formData.get('price_per_day');
            const paymentMethod = formData.get('payment_method');

            if (!paymentMethod) {
                alert('Silakan pilih metode pembayaran');
                return;
            }

            const start = new Date(startDate);
            const end = new Date(endDate);

            // Hitung selisih dalam jam
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            const totalPrice = diffDays * pricePerDay;

            // Format tanggal dan waktu untuk pesan WhatsApp
            const formatDateTime = (date) => {
                return new Date(date).toLocaleString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            };

            let message = `Halo, saya ingin mengkonfirmasi penyewaan mobil dengan detail berikut:
        
Nama: ${customerName}
Mobil: ${carName}
Mulai: ${formatDateTime(startDate)}
Selesai: ${formatDateTime(endDate)}
Total Hari: ${diffDays}
Total Biaya: Rp ${totalPrice.toLocaleString('id-ID')}
Metode Pembayaran: ${paymentMethod === 'cash' ? 'Cash' : 'Transfer Bank'}`;

            if (paymentMethod === 'transfer') {
                message += `\n\nInformasi Rekening:
BCA: 1234567890
A/N: Nama Pemilik`;
            }

            message += "\n\nMohon konfirmasi pemesanan saya. Terima kasih!";

            const phoneNumber = '6282328715044';
            const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;

            try {
                await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });

                window.location.href = whatsappUrl;
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses pemesanan');
            }
        }
    </script>
</div>
