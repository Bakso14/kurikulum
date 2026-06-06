<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KRS Drag & Drop Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
 
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto p-6">
        {{ $slot }}
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
</body>
</html>