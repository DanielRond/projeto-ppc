<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | PPC System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
<div id="app" class="container mx-auto p-8">

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Propostas de Cursos</h1>
        <button onclick="localStorage.removeItem('token'); window.location.href='/';"
                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded shadow transition">
            Sair
        </button>
    </div>

    <course-list></course-list>

</div>
</body>
</html>
