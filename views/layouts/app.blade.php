<!DOCTYPE html>
<html lang="es">
<head>
 <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Bienestar Digital')</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

</head>
<body class="bg-gray-50 font-sans text-gray-900">
    <!-- Barra superior -->
    <nav class="bg-gradient-to-r from-green-500 to-purple-600 text-white px-6 py-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Mi Bienestar Digital</h1>
            <div class="space-x-4">
                <a href="{{ url('/') }}" class="hover:underline">Inicio</a>
                <a href="{{ route('especialistas.index') }}" class="hover:underline">Especialistas</a>
                <a href="{{ route('consejos.index') }}" class="hover:underline">Consejos</a>
            </div>
        </div>
    </nav>

    <!-- Contenido -->
    <main class="container mx-auto px-6 py-10">
        @yield('content')
    </main>
</body>
</html>

