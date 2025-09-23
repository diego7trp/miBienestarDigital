<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Registrados - Admin</title>
    <link rel="stylesheet" href="{{ asset('css/Admin_gestionarusuarios.css') }}">
</head>
<body>
    <div class="tabla-container">
        <h2>Usuarios Registrados</h2>
        <p>Consulta, edita o elimina usuarios registrados en el sistema.</p>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->nombre }}</td>
                    <td>{{ $usuario->correo }}</td>
                    <td>{{ $usuario->rol }}</td>
                    <td>{{ $usuario->estado }}</td>
                    <td class="acciones">
                        <button class="ver">Ver</button>
                        <button class="editar">Editar</button>
                        <button class="eliminar">Eliminar</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>