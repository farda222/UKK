<div>
    <?php
    // Letakkan fungsi ini di helper atau service class
    function getUserColor($identifier)
    {
        // Array warna-warna yang menarik dan kontras
        $colors = ['bg-blue-500', 'bg-red-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-pink-500', 'bg-indigo-500', 'bg-teal-500', 'bg-orange-500', 'bg-cyan-500'];
    
        // Menggunakan ID atau string lain sebagai seed untuk mendapatkan warna yang konsisten
        $hash = crc32($identifier);
        $colorIndex = abs($hash) % count($colors);
    
        return $colors[$colorIndex];
    }
    ?>

    <nav x-data="{ mobileMenuIsOpen: false }" @click.away="mobileMenuIsOpen = false" class="flex items-center justify-between px-6 py-4"
        aria-label="JavaRent menu">
        <!-- Brand Logo -->
        <a href="#" class="text-2xl font-bold text-neutral-900 dark:text-blue-500">
            JavaRent
        </a>

        <!-- Desktop Menu -->
        <ul class="hidden items-center gap-4 sm:flex">
            <li><a href="{{ route('home') }}"
                    class="font-bold text-black underline-offset-2 hover:text-black focus:outline-none focus:underline dark:text-blue-500 dark:hover:text-blue-700 hover:transition-all duration-300 ease-in-out hover:underline"
                    aria-current="page">Home</a></li>
            <li><a href="{{ route('product') }}"
                    class="font-medium text-neutral-600 underline-offset-2 hover:text-black focus:outline-none focus:underline dark:text-blue-500 dark:hover:text-blue-700 hover:transition-all duration-300 ease-in-out hover:underline">Product</a>
            </li>
            <li><a href="{{ route('rental.history') }}"
                    class="font-medium text-neutral-600 underline-offset-2 hover:text-black focus:outline-none focus:underline dark:text-blue-500 dark:hover:text-blue-700 hover:transition-all duration-300 ease-in-out hover:underline">
                    Rental
                </a></li>
            <!-- User Dropdown -->
            <li x-data="{ userDropDownIsOpen: false }" @keydown.esc.window="userDropDownIsOpen = false"
                class="relative flex items-center">
                <button @click="userDropDownIsOpen = !userDropDownIsOpen" :aria-expanded="userDropDownIsOpen"
                    class="rounded-full focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black dark:focus-visible:outline-white">
                    <div
                        class="size-10 rounded-full {{ getUserColor(Auth::user()->email) }} flex items-center justify-center text-white font-medium">
                        {{ Str::upper(Str::substr(Auth::user()->name, 0, 2)) }}
                    </div>
                </button>
                <ul x-show="userDropDownIsOpen" x-transition.opacity x-cloak @click.outside="userDropDownIsOpen = false"
                    @keydown.down.prevent="$focus.wrap().next()" @keydown.up.prevent="$focus.wrap().previous()"
                    class="absolute right-0 top-12 flex w-full min-w-[12rem] flex-col overflow-hidden rounded-md border border-neutral-300 bg-neutral-50 py-1.5 dark:border-neutral-700 dark:bg-neutral-900">
                    <!-- User Info -->
                    <li class="border-b border-neutral-300 dark:border-neutral-700">
                        <div class="flex flex-col px-4 py-2">
                            <span
                                class="text-sm font-medium text-neutral-900 dark:text-white">{{ Auth::user()->name }}</span>
                            <p class="text-xs text-neutral-600 dark:text-neutral-300">{{ Auth::user()->email }}</p>
                        </div>
                    </li>
                    <!-- Menu Items -->
                    <li><a href="{{ url('profile') }}"
                            class="block bg-neutral-50 px-4 py-2 text-sm text-neutral-600 hover:bg-neutral-900/5 hover:text-neutral-900 focus-visible:bg-neutral-900/10 focus-visible:text-neutral-900 focus-visible:outline-none dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-50/5 dark:hover:text-white dark:focus-visible:bg-neutral-50/10 dark:focus-visible:text-white">Edit
                            Profile</a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="block bg-neutral-50 px-4 text-left py-2 text-sm text-neutral-600 hover:bg-neutral-900/5 hover:text-neutral-900 focus-visible:bg-neutral-900/10 focus-visible:text-neutral-900 focus-visible:outline-none dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-50/5 dark:hover:text-white dark:focus-visible:bg-neutral-50/10 dark:focus-visible:text-white w-full">
                                Sign Out
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- Mobile Menu Button -->
        <button @click="mobileMenuIsOpen = !mobileMenuIsOpen" :aria-expanded="mobileMenuIsOpen"
            class="flex text-neutral-600 dark:text-neutral-300 sm:hidden" aria-label="mobile menu"
            aria-controls="mobileMenu">
            <svg x-cloak x-show="!mobileMenuIsOpen" xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true"
                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
            <svg x-cloak x-show="mobileMenuIsOpen" xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true"
                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>


        <!-- Mobile Menu -->
        <ul x-cloak x-show="mobileMenuIsOpen" @click.away="mobileMenuIsOpen = false"
            x-transition:enter="transition motion-reduce:transition-none ease-out duration-300"
            x-transition:enter-start="-translate-y-full" x-transition:enter-end="translate-y-0"
            x-transition:leave="transition motion-reduce:transition-none ease-out duration-300"
            x-transition:leave-start="translate-y-0" x-transition:leave-end="-translate-y-full"
            class="fixed max-h-svh overflow-y-auto inset-x-0 top-0 z-10 flex flex-col rounded-b-md border-b border-neutral-300 bg-neutral-50 px-8 pb-6 pt-10 dark:border-neutral-700 dark:bg-neutral-900 sm:hidden">
            <li class="mb-4 border-none">
                <div class="flex items-center gap-2 py-2">
                    <div
                        class="size-10 rounded-full {{ getUserColor(Auth::user()->email) }} flex items-center justify-center text-white font-medium">
                        {{ Str::upper(Str::substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <span class="font-medium text-neutral-900 dark:text-white">{{ Auth::user()->name }}</span>
                        <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </li>
            <li class="p-2"><a href="{{ route('home') }}"
                    class="w-full text-lg font-medium text-neutral-600 focus:underline dark:text-neutral-300"
                    aria-current="page">Home</a></li>
            <li class="p-2"><a href="{{ route('product') }}"
                    class="w-full text-lg font-medium text-neutral-600 focus:underline dark:text-neutral-300">Product</a>
            </li>
            <li class="p-2"><a href="{{ route('rental.history') }}"
                    class="w-full text-lg font-medium text-neutral-600 focus:underline dark:text-neutral-300">Rental</a>
            </li>
            <hr role="none" class="my-2 border-outline dark:border-neutral-700">
            <li class="p-2"><a href="{{ url('profile') }}"
                    class="w-full text-neutral-600 focus:underline dark:text-neutral-300">Edit Profile</a></li>
            <!-- CTA Button -->
            <li class="mt-4 w-full border-none">
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="rounded-md w-full bg-white px-4 py-2 block text-center font-medium tracking-wide text-neutral-100 hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0 dark:bg-white dark:text-black dark:focus-visible:outline-white">
                        Sign Out
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</div>
