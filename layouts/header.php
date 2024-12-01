<?php
session_start();

$current_page = trim($_SERVER['REQUEST_URI'], '/');

if (!isset($_SESSION['user'])) {
    header("Location: /");
    exit();
}

$user = $_SESSION['user'];

if (($user->role === 'host') && (($current_page !== 'prompts') && ($current_page !== 'profile'))) {
    header("Location: /prompts");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prompter</title>
    
    <!-- Style dan Script -->
    <link href="/layouts/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.5.0/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-900 text-white p-10" x-data="{ dropdownOpen: false }">

    <div class="max-w-5xl mx-auto">
        <?php include ('navigation.php'); ?>
