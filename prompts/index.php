<?php include ('../layouts/header.php'); ?>

<div x-data="crud()" x-init="init()" class="bg-gray-800 p-5 rounded-lg shadow-md space-y-4">

    <div class="flex flex-wrap items-center justify-between mt-4">
        <div class="flex-grow flex justify-between items-center w-full sm:w-auto mb-2 sm:mb-0">
            <input type="text" x-model="searchQuery" @input="searchData" placeholder="Search..." class="w-full sm:w-auto bg-gray-700 text-white px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-sm"/>
        </div>

        <?php if($_SESSION['user']->role != 'host') : ?>
            <button @click="openModal()" class="bg-green-600 text-white px-4 py-2 rounded-sm focus:outline-none hover:bg-green-500">
                <div class="flex gap-2 items-center">
                    <i class="fas fa-plus-circle"></i>
                    New Prompt
                </div>
            </button>
        <?php endif; ?>
    </div>

    <!-- Membuat tabel menjadi responsif dan stack pada layar kecil -->
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto mt-4 border border-gray-600 hidden sm:table">
            <thead class="bg-gray-700">
                <tr class="text-white uppercase text-sm sm:text-base">
                    <th class="px-4 py-2 text-left cursor-pointer" @click="sortTable('title')">
                        <div class="flex gap-2 items-center">
                            Prompt Title
                            <span :class="{'transform rotate-180': sortBy === 'title' && !sortAsc, 'transform rotate-0': sortBy !== 'title'}">
                                <i class="fas fa-sort"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-4 py-2 text-left cursor-pointer w-5" @click="sortTable('showcase')">
                        <div class="flex gap-2 items-center">
                            Showcase
                            <span :class="{'transform rotate-180': sortBy === 'showcase' && !sortAsc, 'transform rotate-0': sortBy !== 'showcase'}">
                                <i class="fas fa-sort"></i>
                            </span>
                        </div>
                    </th>
                    <?php if($_SESSION['user']->role != 'host') : ?>
                        <th class="px-4 py-2 text-left cursor-pointer w-60" @click="sortTable('studio')">
                            <div class="flex gap-2 items-center">
                                Studio Name
                                <span :class="{'transform rotate-180': sortBy === 'studio' && !sortAsc, 'transform rotate-0': sortBy !== 'studio'}">
                                    <i class="fas fa-sort"></i>
                                </span>
                            </div>
                        </th>
                    <?php endif; ?>
                    <th class="px-4 py-2 text-right w-20">Action</th>
                </tr>
            </thead>

            <tbody class="bg-gray-800 text-sm sm:text-base">
                <template x-if="filteredRecords.length === 0">
                    <tr>
                        <td colspan="3" class="px-4 py-2 text-center text-white italic">
                            No data available
                        </td>
                    </tr>
                </template>

                <template x-for="record in paginatedRecords" :key="record.id">
                    <tr class="border-t border-gray-600 hover:bg-gray-700">
                        <td class="px-4 py-2 text-white" x-text="record.title"></td>
                        <td class="px-4 py-2 text-white text-left" x-text="'Etalase ' + record.showcase"></td>
                        <?php if($_SESSION['user']->role != 'host') : ?>
                            <td class="px-4 py-2 text-white" x-text="record.studio"></td>
                        <?php endif; ?>
                        <td class="px-4 py-2 text-right">
                            <div class="flex gap-2 sm:gap-4 items-center justify-end">
                                <button @click="teleprompter(record)" class="text-green-400 hover:text-green-300">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if($_SESSION['user']->role != 'host') : ?>
                                    <button @click="editData(record)" class="text-yellow-400 hover:text-yellow-300">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button @click="deleteData(record)" class="text-red-400 hover:text-red-300">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        <!-- Tampilan stack untuk perangkat mobile -->
        <div class="sm:hidden">
            <template x-for="record in paginatedRecords" :key="record.id">
                <div class="border border-gray-600 rounded-lg p-4 mb-4 bg-gray-700 text-white">
                    <div class="mb-2">
                        <span class="font-bold">Prompt Title:</span> <span x-text="record.title"></span>
                    </div>
                    <div class="mb-2">
                        <span class="font-bold">Showcase:</span> <span x-text="'Etalase ' + record.showcase"></span>
                    </div>
                    <?php if($_SESSION['user']->role != 'host') : ?>
                        <div class="mb-2">
                            <span class="font-bold">Studio Name:</span> <span x-text="record.studio"></span>
                        </div>
                    <?php endif; ?>
                    <div class="flex items-center justify-end space-x-4">
                        <button @click="teleprompter(record)" class="text-green-400 hover:text-green-300">
                            <i class="fas fa-eye"></i>
                        </button>
                        <?php if($_SESSION['user']->role != 'host') : ?>
                            <button @click="editData(record)" class="text-yellow-400 hover:text-yellow-300">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button @click="deleteData(record)" class="text-red-400 hover:text-red-300">
                                <i class="fas fa-trash"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-between mt-4 space-y-2 sm:space-y-0">
        <div>
            <div x-show="message" x-transition.opacity x-text="message" class="text-xs italic transition-opacity duration-1000 ease-in-out"></div>
        </div>
        <div class="flex gap-4 items-center justify-center">
            <div class="text-white text-xs italic">
                <span>Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span></span>
            </div>
            <button @click="changePage('prev')" :disabled="currentPage === 1" class="bg-gray-700 text-white px-3 sm:px-4 py-2 rounded-sm disabled:opacity-50">Prev</button>
            <button @click="changePage('next')" :disabled="currentPage === totalPages" class="bg-gray-700 text-white px-3 sm:px-4 py-2 rounded-sm disabled:opacity-50">Next</button>
        </div>
    </div>

    <?php include('form.php') ?>
    <?php include('prompter.php') ?>

</div>

<script src="crud.js"></script>

<?php include ('../layouts/footer.php'); ?>
