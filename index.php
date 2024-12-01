<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livestreaming Store</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.5.0/dist/cdn.min.js" defer></script>
    <style>
        .letter {
            display: inline-block;
            opacity: 0;
            animation: fadeInUp 0.5s forwards;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body x-data="login" class="bg-gray-900 text-white h-screen overflow-hidden"  x-init="setTimeout(() => showFadeIn = true, 2000)">
    
    <div class="absolute inset-0 overflow-hidden">
        <img src="images/background.jpg" alt="Background" class="w-full h-full object-cover object-center opacity-5">
    </div>

    <header class="relative z-10 px-6 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">Prompter</h1>
        <nav class="space-x-4">
            
            <?php session_start(); ?>
            <?php if(! isset($_SESSION['user'])) : ?>
                <button @click="openModal" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded">Login</button>
            <?php else: ?>
                <a href="/prompts"><button class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded">Prompts</button></a>
            <?php endif; ?>

        </nav>
    </header>

    <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-4">
        <h2 class="text-4xl md:text-6xl font-bold text-white mb-6">
            Bicara Tanpa Henti
        </h2>
        
        <p class="text-lg md:text-xl text-gray-300 max-w-lg mx-auto mb-8">
            <span x-html="'Live streaming lancar penuh percaya diri'.split(' ').map((word) => word.split('').map((letter, i) => `<span class=\'letter\' style=\'animation-delay:${i * 0.1}s\'>${letter}</span>`).join('') + ' ').join('')"></span>
        </p>
    </div>

    <?php include ('auth/login/form.php'); ?>
    <script src="/auth/login/login.js"></script>

</body>
</html>
