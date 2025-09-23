<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel del Administrador | Mi Bienestar Digital</title>
  <link rel="stylesheet" href="{{ asset('css/admin_panel.css') }}">
</head>
<body>

  <aside>
    <h2>Administrador</h2>
    <nav>
      <a href="{{ route('admin.panel') }}">Inicio</a>
      <a href="{{ route('admin.usuarios') }}">Usuarios</a>
      <a href="{{ route('admin.reportes') }}">Reportes</a>
      <a href="{{ route('logout') }}">Cerrar Sesión</a>
    </nav>
  </aside>

  <div class="main">
    <div class="card">
      <h2>Bienvenido, Administrador</h2>
      <p>Gestione la información de los usuarios y supervise el progreso en sus hábitos.</p>
    </div>

    <div class="card">
      <h3>Usuarios Registrados</h3>
      <p>Consulta, edita o elimina usuarios registrados en el sistema.</p>
      <a href="{{ route('admin.usuarios') }}" class="btn">Gestionar Usuarios</a>
    </div>

    <div class="card">
      <h3>Ver Reportes</h3>
      <p>Revisa estadísticas de cumplimiento, avances y hábitos comunes.</p>
      <a href="{{ route('admin.reportes') }}" class="btn">Ver Reportes</a>
    </div>
  </div>

</body>
</html>