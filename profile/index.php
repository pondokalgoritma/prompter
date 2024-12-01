<?php include('../layouts/header.php'); ?>


<div class="bg-gray-900 bg-opacity-50">

    <div class="grid grid-cols-2 gap-4">

        <div class="bg-gray-800 rounded-md shadow-md w-full space-y-4">
            <h3 class="border-b border-gray-700 rounded-t-md px-6 py-3 font-bold text-white">
                Edit Profile
            </h3>

            <div class="px-6 pb-6">
                <form x-data="profile" x-init="init" @submit.prevent="saveData" autocomplete="off" class="space-y-4">
                    <div>
                        <label class="block text-gray-300 text-sm">Full Name<span class="text-xs text-red-500">*</span></label>
                        <input type="text" x-model="form.full_name" @input="clearError('full_name')" class="w-full bg-gray-700 text-white p-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-sm">
                        <span x-show="errors.full_name" class="text-red-400 text-xs italic" x-text="errors.full_name"></span>
                    </div>

                    <div>
                        <label class="block text-gray-300 text-sm">Mobile Number<span class="text-xs text-red-500">*</span></label>
                        <input type="text" x-model="form.mobile_number" @input="clearError('mobile_number')" class="w-full bg-gray-700 text-white p-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-sm">
                        <span x-show="errors.mobile_number" class="text-red-400 text-xs italic" x-text="errors.mobile_number"></span>
                    </div>
                    
                    <div x-show="message" x-transition.opacity x-text="message" class="text-xs italic transition-opacity duration-1000 ease-in-out" ></div>

                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-sm text-sm focus:outline-none hover:bg-blue-400">
                        <div class="flex gap-2 items-center">
                            <i class="fas fa-save"></i> Update
                        </div>
                    </button>
                </form>
            </div>

        </div>


        <div class="bg-gray-800 rounded-md shadow-md w-full space-y-4">
            <h3 class="border-b border-gray-700 rounded-t-md px-6 py-3 font-bold text-white">
                Edit Password
            </h3>
            
            <div class="px-6 pb-6">
                <form  x-data="password" x-init="init" @submit.prevent="saveData" autocomplete="off" class="space-y-4">

                    <div>
                        <label class="block text-gray-300 text-sm">Old Password<span class="text-xs text-red-500">*</span></label>
                        <input type="password" x-model="form.current_password" @input="clearError('current_password')" class="w-full bg-gray-700 text-white p-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-sm">
                        <span x-show="errors.current_password" class="text-red-400 text-xs italic" x-text="errors.current_password"></span>
                    </div>

                    <div>
                        <label class="block text-gray-300 text-sm">New Password<span class="text-xs text-red-500">*</span></label>
                        <input type="password" x-model="form.new_password" @input="clearError('new_password')" class="w-full bg-gray-700 text-white p-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-sm">
                        <span x-show="errors.new_password" class="text-red-400 text-xs italic" x-text="errors.new_password"></span>
                    </div>

                    <div>
                        <label class="block text-gray-300 text-sm">Confirm Password<span class="text-xs text-red-500">*</span></label>
                        <input type="password" x-model="form.confirm_password" @input="clearError('confirm_password')" class="w-full bg-gray-700 text-white p-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-sm">
                        <span x-show="errors.confirm_password" class="text-red-400 text-xs italic" x-text="errors.confirm_password"></span>
                    </div>
                    
                    <div x-show="message" x-transition.opacity x-text="message" class="text-xs italic transition-opacity duration-1000 ease-in-out" ></div>

                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-sm text-sm focus:outline-none hover:bg-blue-400">
                        <div class="flex gap-2 items-center">
                            <i class="fas fa-save"></i> Update
                        </div>
                    </button>

                </form>
            </div>

        </div>
    </div>
    
</div>

<script src="profile_crud.js"></script>
<script src="password_crud.js"></script>

<?php include('../layouts/footer.php'); ?>