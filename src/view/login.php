<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Acceso | SIRE</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    body {
      background: #0A0F1C;
      color: #fff;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
      position: relative;
      overflow-x: hidden;
    }

    .background-animation {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      opacity: 0.1;
    }

    .grid-dots {
      position: absolute;
      width: 100%;
      height: 100%;
      background-image: radial-gradient(#1E2A5A 1px, transparent 1px);
      background-size: 30px 30px;
    }

    .login-container {
      display: flex;
      width: 100%;
      max-width: 900px;
      height: auto;
      min-height: 550px;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
      animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .brand-side {
      flex: 1;
      background: linear-gradient(135deg, #1E2A5A 0%, #0A0F1C 100%);
      padding: 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    .brand-side::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    }

    .logo {
      display: flex;
      align-items: center;
      margin-bottom: 30px;
    }

    .logo-icon {
      width: 60px;
      height: 60px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      font-size: 28px;
      color: #5D7BEF;
    }

    .logo-text {
      font-size: 32px;
      font-weight: 700;
      letter-spacing: 1px;
    }

    .brand-content {
      max-width: 350px;
    }

    .brand-title {
      font-size: 36px;
      font-weight: 700;
      margin-bottom: 20px;
      line-height: 1.2;
    }

    .brand-subtitle {
      font-size: 16px;
      line-height: 1.6;
      color: rgba(255, 255, 255, 0.7);
      margin-bottom: 30px;
    }

    .features {
      list-style: none;
      margin-top: 40px;
    }

    .features li {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
      font-size: 15px;
    }

    .features i {
      margin-right: 12px;
      color: #5D7BEF;
    }

    .login-side {
      flex: 1;
      background: #0A0F1C;
      padding: 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      position: relative;
    }

    .login-side::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, transparent 70%, rgba(30, 42, 90, 0.3) 100%);
    }

    .login-header {
      margin-bottom: 40px;
      text-align: center;
    }

    .login-title {
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 10px;
    }

    .login-subtitle {
      color: rgba(255, 255, 255, 0.6);
      font-size: 15px;
    }

    .form-group {
      margin-bottom: 25px;
      position: relative;
    }

    .form-label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      font-size: 14px;
      color: rgba(255, 255, 255, 0.8);
    }

    .input-with-icon {
      position: relative;
    }

    .form-input {
      width: 100%;
      padding: 15px 15px 15px 45px;
      background: rgba(30, 42, 90, 0.3);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 10px;
      color: #fff;
      font-size: 15px;
      transition: all 0.3s;
    }

    .form-input:focus {
      outline: none;
      border-color: #5D7BEF;
      box-shadow: 0 0 0 2px rgba(93, 123, 239, 0.2);
    }

    .input-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: rgba(255, 255, 255, 0.5);
    }

    .password-toggle {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: rgba(255, 255, 255, 0.5);
      cursor: pointer;
    }

    .btn-login {
      width: 100%;
      padding: 15px;
      background: linear-gradient(135deg, #5D7BEF 0%, #3A56DB 100%);
      border: none;
      border-radius: 10px;
      color: white;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      margin-bottom: 25px;
      position: relative;
      overflow: hidden;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 7px 15px rgba(93, 123, 239, 0.4);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .btn-login::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }

    .btn-login:hover::before {
      left: 100%;
    }

    .forgot-password {
      text-align: center;
      margin-top: 24px;
    }

    .forgot-password a {
      color: rgba(255, 255, 255, 0.6);
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .forgot-password a:hover {
      color: #5D7BEF;
    }

    /* Estados de carga - MANTENIDOS DEL ORIGINAL */
    .loading {
      pointer-events: none;
      opacity: 0.7;
    }

    .loading .btn-login::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 20px;
      height: 20px;
      margin: -10px 0 0 -10px;
      border: 2px solid transparent;
      border-top: 2px solid white;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Responsive */
    @media (max-width: 768px) {
      .login-container {
        flex-direction: column;
        max-width: 450px;
      }
      
      .brand-side, .login-side {
        padding: 40px 30px;
      }
      
      .brand-side {
        text-align: center;
      }
      
      .logo {
        justify-content: center;
      }
    }
    
    @media (max-width: 480px) {
      .brand-side, .login-side {
        padding: 30px 20px;
      }
      
      .brand-title {
        font-size: 28px;
      }
      
      .login-title {
        font-size: 24px;
      }
      
      .logo-text {
        font-size: 28px;
      }
    }

    /* Mejoras de accesibilidad */
    .form-input:focus-visible {
      outline: 2px solid #5D7BEF;
      outline-offset: 2px;
    }

    .btn-login:focus-visible {
      outline: 2px solid rgba(255, 255, 255, 0.5);
      outline-offset: 2px;
    }
  </style>
  <!-- Sweet Alerts css -->
  <link href="<?php echo BASE_URL; ?>src/view/pp/plugins/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script>
    const base_url = '<?php echo BASE_URL; ?>';
    const base_url_server = '<?php echo BASE_URL_SERVER; ?>';
  </script>
</head>

<body>
  <div class="background-animation">
    <div class="grid-dots"></div>
  </div>

  <div class="login-container">
    <div class="brand-side">
      <div class="brand-content">
        <div class="logo">
          <div class="logo-icon">
            <i class="fas fa-graduation-cap"></i>
          </div>
          <div class="logo-text">SIRE</div>
        </div>
        <h1 class="brand-title">Sistema Integral de Registro Estudiantil</h1>
        <p class="brand-subtitle">Plataforma educativa para la gestión académica institucional completa y eficiente.</p>
        
        <ul class="features">
          <li><i class="fas fa-check-circle"></i> Gestión integral de estudiantes</li>
          <li><i class="fas fa-check-circle"></i> Control académico y calificaciones</li>
          <li><i class="fas fa-check-circle"></i> Reportes en tiempo real</li>
        </ul>
      </div>
    </div>
    
    <div class="login-side">
      <div class="login-header">
        <h2 class="login-title">Iniciar Sesión</h2>
        <p class="login-subtitle">Ingresa tus credenciales para acceder</p>
      </div>
      
      <!-- FORMULARIO ORIGINAL - SIN MODIFICAR ESTRUCTURA -->
      <form id="frm_login">
        <div class="form-group">
          <div class="input-with-icon">
            <i class="fas fa-user input-icon"></i>
            <input type="text" name="dni" id="dni" class="form-input" placeholder="Ingresa tu DNI" required>
          </div>
        </div>

        <div class="form-group">
          <div class="input-with-icon">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" name="password" id="password" class="form-input" placeholder="Ingresa tu contraseña" required>
            <button type="button" class="password-toggle" id="togglePassword">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn-login">
          <span>Iniciar Sesión</span>
        </button>
      </form>

      <div class="forgot-password">
        <a href="#">¿Olvidaste tu contraseña?</a>
      </div>
    </div>
  </div>

  <script>
    // Funcionalidad para mostrar/ocultar contraseña - COMPATIBLE
    document.getElementById('togglePassword').addEventListener('click', function() {
      const passwordInput = document.getElementById('password');
      const icon = this.querySelector('i');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
    
    // MANTENIENDO LA FUNCIONALIDAD ORIGINAL DEL FORMULARIO
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('frm_login');
      const submitBtn = form.querySelector('.btn-login');
      
      // El evento submit se maneja en sesion.js - no lo sobrescribimos
      // Solo añadimos efectos visuales que no interfieren
      
      // Efectos de focus mejorados (solo visuales)
      const inputs = document.querySelectorAll('.form-input');
      inputs.forEach(input => {
        input.addEventListener('focus', function() {
          this.parentElement.style.borderColor = '#5D7BEF';
        });
        
        input.addEventListener('blur', function() {
          this.parentElement.style.borderColor = 'rgba(255, 255, 255, 0.1)';
        });
      });
    });
    
    // Efecto de partículas sutiles en el fondo (solo decorativo)
    document.addEventListener('DOMContentLoaded', function() {
      const brandSide = document.querySelector('.brand-side');
      
      for (let i = 0; i < 12; i++) {
        const dot = document.createElement('div');
        dot.style.position = 'absolute';
        dot.style.width = '4px';
        dot.style.height = '4px';
        dot.style.backgroundColor = 'rgba(255, 255, 255, 0.3)';
        dot.style.borderRadius = '50%';
        dot.style.left = Math.random() * 100 + '%';
        dot.style.top = Math.random() * 100 + '%';
        dot.style.animation = `float ${5 + Math.random() * 10}s infinite ease-in-out`;
        brandSide.appendChild(dot);
      }
      
      const style = document.createElement('style');
      style.textContent = `
        @keyframes float {
          0%, 100% { transform: translateY(0) translateX(0); opacity: 0.7; }
          50% { transform: translateY(-15px) translateX(5px); opacity: 1; }
        }
      `;
      document.head.appendChild(style);
    });
  </script>

  <!-- MANTENIENDO TODOS LOS SCRIPTS ORIGINALES -->
  <script src="<?php echo BASE_URL; ?>src/view/js/sesion.js"></script>
  <!-- Sweet Alerts Js-->
  <script src="<?php echo BASE_URL ?>src/view/pp/plugins/sweetalert2/sweetalert2.min.js"></script>
</body>
</html>