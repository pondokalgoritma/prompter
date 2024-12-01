<nav class="bg-gray-800 p-2 rounded-lg shadow-md mb-5" x-data="{ currentPath: '', dropdownOpen: false }" x-init="currentPath = window.location.pathname">
    <div class="flex justify-between items-center">
        
        <!-- Logo atau Ikon Home -->
        <div class="flex items-center space-x-4 px-4 uppercase">
            <a href="/" class="text-white">
                <i class="fas fa-home text-3xl mr-3"></i>
            </a>

            <!-- Menu Navigasi Utama (Ditampilkan pada layar besar) -->
            <div class="hidden md:flex space-x-4">
                <?php if ($_SESSION['user']->role === 'admin') : ?>
                    <a href="/studios" class="text-white p-1 rounded-sm border-b-2 border-transparent hover:border-red-600"
                        :class="{ 'border-blue-600': currentPath === '/studios' }">
                        Studios
                    </a>

                    <a href="/managers" class="text-white p-1 rounded-sm border-b-2 border-transparent hover:border-red-600"
                        :class="{ 'border-blue-600': currentPath === '/managers' }">
                        Managers
                    </a>
                    
                    <a href="/hosts" class="text-white p-1 rounded-sm border-b-2 border-transparent hover:border-red-600"
                        :class="{ 'border-blue-600': currentPath === '/hosts' }">
                        Hosts
                    </a>
                <?php endif; ?>
                
                <a href="/prompts" class="text-white p-1 rounded-sm border-b-2 border-transparent hover:border-red-600"
                    :class="{ 'border-blue-600': currentPath === '/prompts' }">
                    Prompts
                </a>
            </div>
        </div>

        <!-- Tombol User & Dropdown untuk Desktop -->
        <div class="hidden md:flex relative" x-data="{ open: false }" @click.away="open = false">
            <button @click="open = !open" class="text-white flex items-center space-x-2 px-1 pr-6 rounded-sm border-b-2 border-transparent hover:text-red-600">
                <i class="fas fa-user-circle text-xl"></i>
            </button>
            <div x-show="open" x-transition class="absolute right-0 mt-2 bg-gray-700 rounded-lg shadow-lg w-48 py-2 z-10">
                <a href="/profile" class="block px-4 py-2 text-white hover:bg-gray-600">Profile</a>
                <hr class="border-b-1 border-gray-600">
                <a href="/logout.php" class="block px-4 py-2 text-white hover:bg-gray-600">Logout</a>
            </div>
        </div>

        <!-- Tombol Hamburger untuk Mobile -->
        <div class="md:hidden">
            <button @click="dropdownOpen = !dropdownOpen" class="text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Dropdown Menu untuk Mobile -->
    <div x-show="dropdownOpen" x-transition class="md:hidden bg-gray-700 text-white mt-2 rounded-lg shadow-lg">
        <a href="/prompts" class="block px-4 py-2 hover:bg-gray-600" :class="{ 'bg-gray-600': currentPath === '/prompts' }">Prompts</a>
        <?php if ($_SESSION['user']->role === 'admin') : ?>
            <a href="/studios" class="block px-4 py-2 hover:bg-gray-600" :class="{ 'bg-gray-600': currentPath === '/studios' }">Studios</a>
            <a href="/managers" class="block px-4 py-2 hover:bg-gray-600" :class="{ 'bg-gray-600': currentPath === '/managers' }">Managers</a>
            <a href="/hosts" class="block px-4 py-2 hover:bg-gray-600" :class="{ 'bg-gray-600': currentPath === '/hosts' }">Hosts</a>
        <?php endif; ?>
        <a href="/profile" class="block px-4 py-2 hover:bg-gray-600" :class="{ 'bg-gray-600': currentPath === '/profile' }">Profile</a>
        <hr class="border-b-1 border-gray-600">
        <a href="/logout.php" class="block px-4 py-2 hover:bg-gray-600">Logout</a>
    </div>
</nav>
