<div>
    <div x-show="showLogin" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-80 z-20 flex items-center justify-center">
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-md w-full" @click.away="closeModal">

            <form @submit.prevent="verify" autocomplete="off" class="space-y-6">

                <div>
                    <input type="text" x-model="form.user_name" @input="clearError('user_name')" placeholder="user name or mobile number" class="w-full bg-gray-700 text-white p-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-sm">
                    <span x-show="errors.user_name" class="text-red-400 text-xs italic" x-text="errors.user_name"></span>
                </div>
                
                <div>
                    <input type="password" x-model="form.password" @input="clearError('password')" placeholder="password" class="w-full bg-gray-700 text-white p-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-sm">
                    <span x-show="errors.password" class="text-red-400 text-xs italic" x-text="errors.password"></span>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded">Login</button>
            </form>

            <div x-show="errorMessage" class="text-red-500 text-sm mt-2">
                <p x-text="errorMessage"></p>
            </div>

            <div x-show="successMessage" class="text-green-500 text-sm mt-2">
                <p x-text="successMessage"></p>
            </div>
        </div>
    </div>
</div>