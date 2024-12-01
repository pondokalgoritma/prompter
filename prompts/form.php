<div x-show="isModalOpen" @click.away="closeModal()" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center p-4">
    <div class="bg-gray-800 rounded-md shadow-md w-full max-w-4xl space-y-4 mx-2 md:mx-0">
        <h3 class="border-b border-gray-700 rounded-t-md px-6 py-3 font-bold text-white" x-text="form.id ? 'Edit Prompt' : 'New Prompt'"></h3>

        <div class="px-6 pb-6">
            <form @submit.prevent="saveData" autocomplete="off" class="space-y-4">
                <input type="hidden" x-model="form.id">

                <!-- Grid layout responsive -->
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div class="md:col-span-3">
                        <label class="block text-gray-300 text-sm">Title <span class="text-xs text-red-500">*</span></label>
                        <input type="text" x-model="form.title" @input="clearError('title')" class="w-full bg-gray-700 text-white p-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-sm">
                        <span x-show="errors.title" class="text-red-400 text-xs italic" x-text="errors.title"></span>
                    </div>

                    <div class="md:col-span-3">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div class="md:col-span-3">
                                <label class="block text-gray-300 text-sm">Studio<span class="text-xs text-red-500">*</span></label>
                                <select x-model="form.studio_id" @change="clearError('studio_id')" class="w-full bg-gray-700 text-white p-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-sm">
                                    <option value="" disabled selected>Select Studio</option>
                                    <template x-for="studio in studios" :key="studio.id">
                                        <option :value="studio.id" x-text="studio.name"></option>
                                    </template>
                                </select>
                                <span x-show="errors.studio_id" class="text-red-400 text-xs italic" x-text="errors.studio_id"></span>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-gray-300 text-sm">Showcase<span class="text-xs text-red-500">*</span></label>
                                <select x-model="form.showcase" @change="clearError('showcase')" class="w-full bg-gray-700 text-white p-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-sm">
                                    <option value="" disabled selected>Etalase</option>
                                    <template x-for="i in 100" :key="i">
                                        <option :value="i" x-text="'Etalase ' + i"></option>
                                    </template>
                                </select>
                                <span x-show="errors.showcase" class="text-red-400 text-xs italic" x-text="errors.showcase"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Responsive textarea -->
                <div>
                    <label class="block text-gray-300 text-sm">Content <span class="text-xs text-red-500">*</span></label>
                    <textarea x-model="form.content" @input="clearError('content')" class="w-full bg-gray-700 text-white p-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-sm" rows="6"></textarea>
                    <span x-show="errors.content" class="text-red-400 text-xs italic" x-text="errors.content"></span>
                </div>

                <!-- Buttons responsive -->
                <div class="flex flex-col md:flex-row items-center gap-4 mt-4 justify-center">
                    <button type="button" @click="closeModal()" class="bg-red-500 text-white px-6 py-2 rounded-sm text-sm focus:outline-none hover:bg-red-400 w-full md:w-auto">
                        <div class="flex gap-2 items-center justify-center">
                            <i class="fas fa-times-circle"></i> Cancel
                        </div>
                    </button>

                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-sm text-sm focus:outline-none hover:bg-blue-400 w-full md:w-auto">
                        <div class="flex gap-2 items-center justify-center">
                            <i class="fas fa-save"></i> <span x-text="form.id ? 'Update' : 'Create'"></span>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
