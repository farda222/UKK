<div>
    <div class="container mx-auto px-4 py-8">
        @if ($isAdmin)
            <div x-data="{ showPopup: true }" x-show="showPopup" class="fixed inset-0 z-50 overflow-y-auto"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <!-- Backdrop -->
                    <div class="fixed inset-0 bg-black opacity-30"></div>

                    <!-- Popup Content -->
                    <div class="relative bg-white rounded-lg shadow-xl p-8 max-w-md w-full" x-show="showPopup"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100">
                        <div class="text-center">
                            <div class="mb-4">
                                <svg class="mx-auto h-12 w-12 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Anda adalah Admin</h3>
                            <p class="text-sm text-gray-500 mb-6">
                                Anda sedang mengakses halaman ini sebagai administrator sistem.
                            </p>
                            <button @click="showPopup = false"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                                Mengerti
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <h1 class="text-3xl font-bold mb-8 text-gray-800">Riwayat Rental</h1>
        <div class="bg-white rounded-xl overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col"
                                class="px-6 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                No
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                Mobil
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                Mulai Sewa
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                Selesai Sewa
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                Total Harga
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($rentals as $index => $rental)
                            <tr class="hover:bg-gray-50 transition-colors duration-200" x-data="{ showCancelModal: false }">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <img class="h-12 w-12 rounded-lg object-cover shadow-sm"
                                                src="{{ !empty($rental->car->image) ? asset('storage/' . $rental->car->image) : asset('images/default-placeholder.jpg') }}"
                                                alt="{{ $rental->car->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $rental->car->name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($rental->mulai_sewa)->format('d M Y 
                                    H:i:s') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($rental->selesai_sewa)->format('d M Y H:i:s') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rp {{ number_format($rental->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'disetujui' => 'bg-green-100 text-green-800 border border-green-200',
                                            'pending' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                            'dibatalkan' => 'bg-red-100 text-red-800 border border-red-200',
                                            'selesai' => 'bg-gray-100 text-gray-800 border border-gray-200',
                                        ];
                                        $statusClass =
                                            $statusClasses[$rental->status] ??
                                            'bg-gray-100 text-gray-800 border border-gray-200';
                                    @endphp
                                    <span
                                        class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst($rental->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($rental->status === 'dibatalkan')
                                        <span class="text-gray-500 text-sm font-medium">
                                            Sudah Dibatalkan
                                        </span>
                                    @elseif($rental->status === 'disetujui')
                                        <span class="text-green-600 text-sm font-medium">
                                            Sudah Disetujui
                                            <br>
                                            (tidak bisa dibatalkan)
                                        </span>
                                    @elseif($rental->status === 'terlambat')
                                        <span class="text-red-600 text-sm font-medium">
                                            Kamu terlambat mengembalikan
                                            <br>
                                            (denda per jam : Rp
                                            {{ number_format($rental->car->penalty, 0, ',', '.') }}/<br>
                                            lewat setengah hari : Rp
                                            {{ number_format($rental->car->price_per_day, 0, ',', '.') }})
                                        </span>
                                    @elseif($rental->status === 'pending')
                                        <button @click="showCancelModal = true"
                                            class="text-red-600 hover:text-red-900 font-medium text-sm">
                                            Batalkan Pesanan
                                        </button>

                                        <!-- Cancel Confirmation Modal -->
                                        <div x-show="showCancelModal"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 transform scale-95"
                                            x-transition:enter-end="opacity-100 transform scale-100"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100 transform scale-100"
                                            x-transition:leave-end="opacity-0 transform scale-95"
                                            class="fixed inset-0 z-50 flex items-center justify-center">
                                            <!-- Backdrop with blur -->
                                            <div x-show="showCancelModal"
                                                x-transition:enter="transition-opacity ease-out duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition-opacity ease-in duration-200"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                                class="fixed inset-0 bg-gray-500/75 backdrop-blur-sm">
                                            </div>

                                            <!-- Modal Content -->
                                            <div x-show="showCancelModal"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0 transform translate-y-4"
                                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                                x-transition:leave="transition ease-in duration-200"
                                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                                x-transition:leave-end="opacity-0 transform translate-y-4"
                                                @click.away="showCancelModal = false"
                                                class="relative bg-white rounded-lg p-6 w-auto mx-auto shadow-xl">
                                                <div class="text-center">
                                                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                    <h3 class="mb-5 text-lg font-normal text-gray-800">
                                                        Apakah anda yakin ingin membatalkan pesanan ini?
                                                    </h3>
                                                    <form action="{{ route('rental.cancel', $rental->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                                            Ya, Batalkan
                                                        </button>
                                                        <button type="button" @click="showCancelModal = false"
                                                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                                            Tidak
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($rental->status === 'selesai')
                                        <span class="text-gray-500 text-sm font-medium">
                                            Sewa Selesai
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center bg-gray-50">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-3" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <span class="font-medium">Tidak ada riwayat rental ditemukan.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
