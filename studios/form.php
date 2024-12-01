<div x-show="isModalOpen" @click.away="closeModal()" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center p-4">
    <div class="bg-gray-800 rounded-md shadow-md w-full max-w-2xl space-y-4 mx-2 md:mx-0">
        <h3 class="border-b border-gray-700 rounded-t-md px-6 py-3 font-bold text-white" x-text="form.id ? 'Edit Studio' : 'New Studio'"></h3>

        <div class="px-6 pb-6">
            <form @submit.prevent="saveData" autocomplete="off" class="space-y-4">
                <input type="hidden" x-model="form.id">

                <!-- Studio Name Field -->
                <div>
                    <label class="block text-gray-300 text-sm">Studio Name<span class="text-xs text-red-500">*</span></label>
                    <input type="text" x-model="form.name" @input="clearError('name')" class="w-full bg-gray-700 text-white p-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-sm">
                    <span x-show="errors.name" class="text-red-400 text-xs italic" x-text="errors.name"></span>
                </div>
                
                <!-- Button Group -->
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
