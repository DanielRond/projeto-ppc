<!DOCTYPE html>
<html>
<head>
    <title>Dashboard | PPC System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
<div id="app" class="p-8">
    <h1 class="text-2xl font-bold">Bem-vindo ao Sistema</h1>
    <p>Você está logado e pronto para gerenciar os cursos.</p>

    <div class="mt-4">
        <button onclick="localStorage.removeItem('token'); window.location.href='/';"
                class="px-4 py-2 bg-red-500 text-white rounded">
            Sair
        </button>
    </div>
</div>
</body>
</html>
