<div>
    <section class="text-gray-600 body-font w-full z-10">
        <div class="px-2 py-5 mx-auto md:mx-6 lg:mx-6 xl:mx-6 2xl:mx-6">
            <div class="flex items-center justify-between mb-5">
                <div class="text-left">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Jelajahi Mobil Kami</h1>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @foreach ($cars as $car)
                    <div class="border-2 rounded-lg transition-shadow duration-300 ease-in-out hover:shadow-lg">
                        <a class="block relative rounded-t-lg overflow-hidden pointer-events-auto" style="height: 200px;">
                            <img alt="Gambar Mobil" class="object-cover object-center w-full h-full block"
                                src="{{ !empty($car->image) ? asset('storage/' . $car->image) : asset('images/default-placeholder.jpg') }}">
                        </a>
                        <div class="p-4">
                            <h2 class="text-gray-900 title-font text-lg font-medium mb-2">{{ $car->name }}</h2>
                            <p class="text-green-600 font-bold mb-2">Rp
                                {{ number_format($car->price_per_day, 0, ',', '.') }}</p>

                            <!-- Tampilan stok dengan warna -->
                            <p class="text-gray-600 mb-4">
                                Stok:
                                <span class="{{ $car->stock > 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                    {{ $car->stock }}
                                </span> unit
                            </p>

                            <!-- Pengecekan stok untuk tombol -->
                            @if ($car->stock < 1)
                                <p class="text-red-500 font-medium mt-2">Stok tidak tersedia</p>
                            @elseif ($car->status == 'maintenance')
                                <p class="text-yellow-500 font-medium mt-2">Sedang dalam perbaikan</p>
                            @else
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <!-- Tambahkan pengecekan stok sebelum menampilkan tombol -->
                                    @if ($car->stock > 0)
                                        <a href="{{ route('checkout.form', $car->id) }}"
                                            class="btn btn-primary bg-blue-500 text-center px-4 py-2 rounded-md text-white hover:bg-blue-600 transition-colors">
                                            Sewa Sekarang
                                        </a>
                                    @endif
                                    <a href="{{ route('product.detail', $car->id) }}"
                                        class="btn btn-primary bg-white text-center px-4 py-2 rounded-md text-black hover:bg-blue-600 hover:text-white transition-colors">
                                        Detail Produk
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
