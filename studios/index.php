<?php include ('../layouts/header.php'); ?>

<div x-data="crud()" x-init="init()" class="bg-gray-800 p-5 rounded-lg shadow-md space-y-4">

    <!-- Search dan Button untuk mobile -->
    <div class="flex flex-col md:flex-row items-center justify-between mt-4 space-y-2 md:space-y-0">
        <input type="text" x-model="searchQuery" @input="searchData" placeholder="Search..." 
            class="bg-gray-700 text-white px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-sm w-full md:w-auto"/>

        <button @click="openModal()" class="bg-green-600 text-white px-4 py-2 rounded-sm focus:outline-none hover:bg-green-500 w-full md:w-auto">
            <div class="flex gap-2 items-center justify-center">
                <i class="fas fa-plus-circle"></i> New Studio
            </div>
        </button>
    </div>
    
    <!-- Table responsive untuk layar besar -->
    <div class="hidden md:block">
        <table class="min-w-full table-auto mt-4 border border-gray-600">
            <thead class="bg-gray-700">
                <tr class="text-white uppercase">
                    <th class="px-4 py-2 text-left cursor-pointer" @click="sortTable('name')">
                        <div class="flex gap-2 items-center">
                            Studio Name
                            <span :class="{'transform rotate-180': sortBy === 'name' && !sortAsc, 'transform rotate-0': sortBy !== 'name'}">
                                <i class="fas fa-sort"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-4 py-2 text-left cursor-pointer w-30" @click="sortTable('prompts')">
                        <div class="flex gap-2 items-center">
                            Prompt
                            <span :class="{'transform rotate-180': sortBy === 'prompts' && !sortAsc, 'transform rotate-0': sortBy !== 'prompts'}">
                                <i class="fas fa-sort"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-4 py-2 text-left cursor-pointer w-30" @click="sortTable('managers')">
                        <div class="flex gap-2 items-center">
                            Managers
                            <span :class="{'transform rotate-180': sortBy === 'managers' && !sortAsc, 'transform rotate-0': sortBy !== 'managers'}">
                                <i class="fas fa-sort"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-4 py-2 text-left cursor-pointer w-30" @click="sortTable('hosts')">
                        <div class="flex gap-2 items-center">
                            Hosts
                            <span :class="{'transform rotate-180': sortBy === 'hosts' && !sortAsc, 'transform rotate-0': sortBy !== 'hosts'}">
                                <i class="fas fa-sort"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-4 py-2 text-right w-30">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody class="bg-gray-800">
                <template x-if="filteredRecords.length === 0">
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-white italic">No data available</td>
                    </tr>
                </template>
                
                <template x-for="record in paginatedRecords" :key="record.id">
                    <tr class="border-t border-gray-600 hover:bg-gray-700">
                        <td class="px-4 py-2 text-white text-left" x-text="record.name"></td>
                        <td class="px-4 py-2 text-white text-left" x-text="record.prompts === 0 ? '-' : record.prompts === 1 ? record.prompts + ' prompt' : record.prompts + ' prompts'"></td>
                        <td class="px-4 py-2 text-white text-left" x-text="record.managers === 0 ? '-' : record.managers === 1 ? record.managers + ' manager' : record.managers + ' managers'"></td>
                        <td class="px-4 py-2 text-white text-left" x-text="record.hosts === 0 ? '-' : record.hosts === 1 ? record.hosts + ' host' : record.hosts + ' hosts'"></td>
                        <td class="px-4 py-2 text-right">
                            <button @click="editData(record)" class="text-yellow-400 hover:text-yellow-300 mr-2">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button @click="deleteData(record)" 
                                :disabled="record.prompts >= 1 || record.hosts >= 1" 
                                :class="{'opacity-25 cursor-not-allowed': record.prompts >= 1 || record.hosts >= 1, 'hover:text-red-300': !(record.prompts >= 1 || record.hosts >= 1)}"
                                class="text-red-400">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Tampilan stack untuk mobile -->
    <div class="md:hidden space-y-4 mt-4">
        <template x-if="filteredRecords.length === 0">
            <div class="text-center text-white italic">No data available</div>
        </template>

        <template x-for="record in paginatedRecords" :key="record.id">
            <div class="border border-gray-600 rounded-lg p-4 bg-gray-700">
                <div class="text-lg font-bold text-white" x-text="record.name"></div>
                <div class="text-sm text-gray-300 mt-1" x-text="'Prompts: ' + (record.prompts === 0 ? '-' : record.prompts)"></div>
                <div class="text-sm text-gray-300 mt-1" x-text="'Hosts: ' + (record.hosts === 0 ? '-' : record.hosts)"></div>
                <div class="flex justify-end mt-4 space-x-4">
                    <button @click="editData(record)" class="text-yellow-400 hover:text-yellow-300">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button @click="deleteData(record)" 
                        :disabled="record.prompts >= 1 || record.hosts >= 1" 
                        :class="{'opacity-25 cursor-not-allowed': record.prompts >= 1 || record.hosts >= 1, 'hover:text-red-300': !(record.prompts >= 1 || record.hosts >= 1)}"
                        class="text-red-400">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </template>
    </div>

    <!-- Pagination Controls -->
    <div class="flex items-center justify-between mt-4">
        <div x-show="message" x-transition.opacity x-text="message" class="text-xs italic transition-opacity duration-1000 ease-in-out"></div>
        <div class="flex gap-4 items-center justify-self-end">
            <div class="text-white text-xs italic">
                <span>Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span></span>
            </div>
            <button @click="changePage('prev')" :disabled="currentPage === 1" class="bg-gray-700 text-white px-4 py-2 rounded-sm disabled:opacity-50">Prev</button>
            <button @click="changePage('next')" :disabled="currentPage === totalPages" class="bg-gray-700 text-white px-4 py-2 rounded-sm disabled:opacity-50">Next</button>
        </div>
    </div>

    <?php include('form.php') ?>
</div>

<script src="crud.js"></script>

<?php include ('../layouts/footer.php'); ?>
