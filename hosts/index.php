<?php include ('../layouts/header.php'); ?>

<div x-data="crud()" x-init="init()" class="bg-gray-800 p-5 rounded-lg shadow-md space-y-4">

    <!-- Search and Add Host Button -->
    <div class="flex flex-col md:flex-row items-center justify-between mt-4 gap-4">
        <input type="text" x-model="searchQuery" @input="searchData" placeholder="Search..." 
            class="w-full md:w-auto bg-gray-700 text-white px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-sm"/>
        <button @click="openModal()" 
            class="bg-green-600 text-white px-4 py-2 rounded-sm focus:outline-none hover:bg-green-500 flex items-center gap-2">
            <i class="fas fa-plus-circle"></i>
            New Host
        </button>
    </div>

    <!-- Responsive Table with Stacked Layout for Small Screens -->
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto mt-4 border border-gray-600">
            <thead class="bg-gray-700 hidden md:table-header-group">
                <tr class="text-white uppercase">
                    <th class="px-4 py-2 text-left cursor-pointer" @click="sortTable('user_name')">
                        <div class="flex items-center gap-2">
                            User Name
                            <span :class="{'transform rotate-180': sortBy === 'user_name' && !sortAsc, 'transform rotate-0': sortBy !== 'user_name'}">
                                <i class="fas fa-sort"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-4 py-2 text-left cursor-pointer" @click="sortTable('full_name')">
                        <div class="flex items-center gap-2">
                            Full Name
                            <span :class="{'transform rotate-180': sortBy === 'full_name' && !sortAsc, 'transform rotate-0': sortBy !== 'full_name'}">
                                <i class="fas fa-sort"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-4 py-2 text-left cursor-pointer" @click="sortTable('mobile_number')">
                        <div class="flex items-center gap-2">
                            Mobile Number
                            <span :class="{'transform rotate-180': sortBy === 'mobile_number' && !sortAsc, 'transform rotate-0': sortBy !== 'mobile_number'}">
                                <i class="fas fa-sort"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-4 py-2 text-left cursor-pointer" @click="sortTable('studio')">
                        <div class="flex items-center gap-2">
                            Studio Name
                            <span :class="{'transform rotate-180': sortBy === 'studio' && !sortAsc, 'transform rotate-0': sortBy !== 'studio'}">
                                <i class="fas fa-sort"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-4 py-2 text-right w-20">Action</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800">
                <!-- Display message if no data is available -->
                <template x-if="filteredRecords.length === 0">
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-white italic">No data available</td>
                    </tr>
                </template>

                <!-- Data Rows with Labeled Stacked Layout on Small Screens -->
                <template x-for="record in paginatedRecords" :key="record.id">
                    <tr class="border-t border-gray-600 hover:bg-gray-700 md:table-row flex flex-col md:flex-row md:flex-wrap p-4 md:p-0 space-y-2 md:space-y-0">
                        <td class="px-4 py-2 text-white md:border-none">
                            <span class="block font-semibold md:hidden">User Name:</span>
                            <span x-text="record.user_name"></span>
                        </td>
                        <td class="px-4 py-2 text-white md:border-none">
                            <span class="block font-semibold md:hidden">Full Name:</span>
                            <span x-text="record.full_name"></span>
                        </td>
                        <td class="px-4 py-2 text-white md:border-none">
                            <span class="block font-semibold md:hidden">Mobile Number:</span>
                            <span x-text="record.mobile_number"></span>
                        </td>
                        <td class="px-4 py-2 text-white md:border-none">
                            <span class="block font-semibold md:hidden">Studio Name:</span>
                            <span x-text="record.studio"></span>
                        </td>
                        <td class="px-4 py-2 text-right md:text-left w-full md:w-24 flex md:block gap-2 md:gap-0 justify-end md:justify-center">
                            <button @click="editData(record)" class="text-yellow-400 hover:text-yellow-300">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button @click="deleteData(record)" class="text-red-400 hover:text-red-300">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button @click="resetPassword(record)" class="text-blue-400 hover:text-blue-300">
                                <i class="fas fa-key"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex flex-col md:flex-row items-center justify-between mt-4 gap-4">
        <div>
            <div x-show="message" x-transition.opacity x-text="message" 
                class="text-xs italic transition-opacity duration-1000 ease-in-out"></div>
        </div>
        <div class="flex gap-4 items-center justify-center md:justify-end">
            <div class="text-white text-xs italic">
                <span>Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span></span>
            </div>
            <button @click="changePage('prev')" :disabled="currentPage === 1" 
                class="bg-gray-700 text-white px-4 py-2 rounded-sm disabled:opacity-50">Prev</button>
            <button @click="changePage('next')" :disabled="currentPage === totalPages" 
                class="bg-gray-700 text-white px-4 py-2 rounded-sm disabled:opacity-50">Next</button>
        </div>
    </div>

    <!-- Include Form Modal -->
    <?php include('form.php') ?>

</div>

<script src="crud.js"></script>

<?php include ('../layouts/footer.php'); ?>
