<div>
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Gambar Mobil -->
            <div class="rounded-lg overflow-hidden">
                <img src="{{ !empty($car->image) ? asset('storage/' . $car->image) : asset('images/default-placeholder.jpg') }}"
                    alt="{{ $car->name }}" class="w-full h-full object-cover">
            </div>

            <!-- Detail Informasi -->
            <div class="space-y-4">
                <h1 class="text-3xl font-bold text-gray-900">{{ $car->name }}</h1>
                <p class="text-2xl text-green-600 font-bold">
                    Rp {{ number_format($car->price_per_day, 0, ',', '.') }} / hari
                </p>

                <!-- Spesifikasi Mobil -->
                <div class="space-y-3">
                    <h2 class="text-xl font-semibold text-gray-900">Spesifikasi</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600">Merek</p>
                            <p class="font-medium">{{ $car->brand }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Tipe</p>
                            <p class="font-medium">{{ $car->type->name }}</p>
                        </div>
                        <div class="w-60">
                            <p class="text-gray-600">Plat Nomor</p>
                            <p class="font-medium">{{ $car->license_plate }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Denda per jam</p>
                            <p class="font-medium">Rp {{ number_format($car->penalty, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Denda per hari</p>
                            <p class="font-medium">Rp {{ number_format($car->price_per_day, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Status</p>
                            <p class="font-medium">
                                @if ($car->status == 'available')
                                    <span class="text-green-600">Tersedia</span>
                                @elseif($car->status == 'rented')
                                    <span class="text-red-500">Sedang Disewa</span>
                                @else
                                    <span class="text-yellow-500">Dalam Perbaikan</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Sewa -->
                @if ($car->status == 'available')
                    <a href="{{ route('checkout.form', $car->id) }}"
                        class="inline-block w-full bg-blue-500 text-white text-center px-6 py-3 rounded-md hover:bg-blue-600 transition-colors">
                        Sewa Sekarang
                    </a>
                @endif

                <!-- Deskripsi -->
                @if ($car->description)
                    <div class="space-y-2">
                        <h2 class="text-xl font-semibold text-gray-900">Deskripsi</h2>
                        <p class="text-gray-600">{{ $car->description }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
