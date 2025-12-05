<?php
include("../database.php");
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin'){ header("Location: ../login.php"); exit; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Crear Usuario</title>
  <link rel="stylesheet" href="../css/usuarios.css">
</head>
<body>
  <h1>Crear Usuario</h1>
  
  <form method="POST" action="usuarios_procesar.php" id="formUsuario">
    <div class="form-group">
      <label>Usuario (username)</label>
      <input type="text" id="username" name="username" required placeholder="Ingresa nombre de usuario">
      <div id="mensaje-usuario" class="mensaje-ajax"></div>
    </div>
    
    <div class="form-group">
      <label>Nombre completo</label>
      <input type="text" name="nombre" placeholder="Nombre completo del usuario">
    </div>
    
    <div class="form-group">
      <label>Correo electrónico</label>
      <input type="email" id="correo" name="correo" required placeholder="ejemplo@correo.com">
      <div id="mensaje-correo" class="mensaje-ajax"></div>
    </div>
    
    <div class="form-group">
      <label>Contraseña</label>
      <input type="password" name="password" required placeholder="Ingresa contraseña">
    </div>
    
    <div class="form-group">
      <label>Rol del usuario</label>
      <select name="rol">
        <option value="usuario">Usuario</option>
        <option value="empleado">Empleado</option>
        <option value="admin">Administrador</option>
      </select>
    </div>
    
    <button name="crear" type="submit" id="btnCrear">Crear Usuario</button>
  </form>

  <!-- JavaScript para AJAX -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
  $(document).ready(function() {
      let usuarioValido = false;
      let correoValido = false;

      // Verificar nombre 
      $('#username').on('blur', function() {
          var username = $(this).val().trim();
          
          if(username.length >= 3) {
              $('#mensaje-usuario').html('🔍 Verificando usuario...').css('color', 'blue');
              
              $.ajax({
                  url: 'verificar_usuario.php',
                  type: 'POST',
                  data: { username: username },
                  success: function(response) {
                      if(response.existe) {
                          $('#mensaje-usuario').html('❌ Este usuario ya existe').css('color', 'red');
                          $('#username').addClass('error');
                          usuarioValido = false;
                      } else {
                          $('#mensaje-usuario').html('✅ Usuario disponible').css('color', 'green');
                          $('#username').removeClass('error');
                          usuarioValido = true;
                      }
                      actualizarBoton();
                  },
                  error: function() {
                      $('#mensaje-usuario').html('⚠️ Error de conexión').css('color', 'orange');
                      usuarioValido = false;
                      actualizarBoton();
                  }
              });
          } else if(username.length > 0) {
              $('#mensaje-usuario').html('ℹ️ Mínimo 3 caracteres').css('color', 'orange');
              usuarioValido = false;
              actualizarBoton();
          } else {
              $('#mensaje-usuario').html('');
              usuarioValido = false;
              actualizarBoton();
          }
      });

      // Verificar correo electrónico con AJAX
      $('#correo').on('blur', function() {
          var correo = $(this).val().trim();
          
          if(correo.length > 0) {
              $('#mensaje-correo').html('🔍 Verificando correo...').css('color', 'blue');
              
              $.ajax({
                  url: 'verificar_usuario.php',
                  type: 'POST',
                  data: { correo: correo },
                  success: function(response) {
                      if(response.existe) {
                          $('#mensaje-correo').html('Este correo ya está registrado').css('color', 'red');
                          $('#correo').addClass('error');
                          correoValido = false;
                      } else {
                          $('#mensaje-correo').html('✅ Correo disponible').css('color', 'green');
                          $('#correo').removeClass('error');
                          correoValido = true;
                      }
                      actualizarBoton();
                  },
                  error: function() {
                      $('#mensaje-correo').html('⚠️ Error de conexión').css('color', 'orange');
                      correoValido = false;
                      actualizarBoton();
                  }
              });
          } else {
              $('#mensaje-correo').html('');
              correoValido = false;
              actualizarBoton();
          }
      });

      function actualizarBoton() {
          if(usuarioValido && correoValido) {
              $('#btnCrear').prop('disabled', false);
          } else {
              $('#btnCrear').prop('disabled', true);
          }
      }

      // Validar formulario antes de enviar
      $('#formUsuario').on('submit', function(e) {
          if(!usuarioValido || !correoValido) {
              e.preventDefault();
              alert('Verifica que el usuario y correo estén disponibles antes de crear.');
              return false;
          }
      });
  });
  </script>

  <style>
  .form-group {
      margin-bottom: 15px;
  }

  label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
  }

  input, select {
      width: 100%;
      max-width: 300px;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
  }

  .mensaje-ajax {
      font-size: 14px;
      margin-top: 5px;
      font-weight: bold;
  }

  .error {
      border-color: #e74c3c !important;
      background-color: #ffe6e6 !important;
  }

  button {
      background: #27ae60;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
  }

  button:hover {
      background: #219150;
  }

  button:disabled {
      background: #95a5a6;
      cursor: not-allowed;
  }
  </style>
</body>
</html>