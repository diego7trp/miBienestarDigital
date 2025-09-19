<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Usuarios Registrados - Admin</title>
  <link rel="stylesheet" href="{{ asset('css/Admin_usuario.css') }}">
</head>
<body>

  <div class="sidebar">
    <h2>Administrador</h2>
    <a href="{{ route('admin.panel') }}">Inicio</a>
    <a href="{{ route('admin.usuarios') }}">Usuarios</a>
    <a href="{{ route('admin.reportes') }}">Reportes</a>
    <a href="{{ route('logout') }}">Cerrar Sesión</a>
  </div>

  <div class="main-content">
    <div class="card">
      <h1>Usuarios Registrados</h1>
      <p>Consulta, edita o elimina usuarios registrados en el sistema.</p>
      <button class="btn-yellow">+ Añadir Usuario</button>

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
          <tr>
            <td>001</td>
            <td>Laura Gómez</td>
            <td>laura@example.com</td>
            <td>Paciente</td>
            <td>Activo</td>
            <td class="acciones">
              <button class="ver">Ver</button>
              <button class="editar">Editar</button>
              <button class="eliminar">Eliminar</button>
            </td>
          </tr>
          <tr>
            <td>002</td>
            <td>Pedro Ríos</td>
            <td>pedro@example.com</td>
            <td>Especialista</td>
            <td>Inactivo</td>
            <td class="acciones">
              <button class="ver">Ver</button>
              <button class="editar">Editar</button>
              <button class="eliminar">Eliminar</button>
            </td>
          </tr>
          <!-- Aquí luego puedes cargar usuarios dinámicamente -->
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
