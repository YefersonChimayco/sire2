<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Correo Empresarial</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }
    .container {
      max-width: 600px;
      margin: auto;
      background-color: #ffffff;
      font-family: Arial, sans-serif;
      color: #333333;
      border: 1px solid #dddddd;
    }
    .header {
      background-color: #004aad;
      color: white;
      padding: 20px;
      text-align: center;
    }
    .content {
      padding: 30px;
    }
    .content h1 {
      font-size: 22px;
      margin-bottom: 20px;
    }
    .content p {
      font-size: 16px;
      line-height: 1.5;
    }
    .button {
      display: inline-block;
      background-color: #004aad;
      color: #ffffff !important;
      padding: 12px 25px;
      margin: 20px 0;
      text-decoration: none;
      border-radius: 4px;
    }
    .footer {
      background-color: #eeeeee;
      text-align: center;
      padding: 15px;
      font-size: 12px;
      color: #666666;
    }
    @media screen and (max-width: 600px) {
      .content, .header, .footer {
        padding: 15px !important;
      }
      .button {
        padding: 10px 20px !important;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>Nombre de tu empresa</h2>
    </div>
    <div class="content">
      <h1>Hola [Nombre del cliente],</h1>
      <p>
        Te saludamos cordialmente. Queremos informarte sobre nuestras últimas novedades y promociones exclusivas para ti.
      </p>
      <p>
        ¡No te pierdas nuestras ofertas especiales por tiempo limitado!
      </p>
      <a href="https://www.tusitio.com/promocion" class="button">Ver más</a>
      <p>Gracias por confiar en nosotros.</p>
    </div>
    <div class="footer">
      © 2025 Nombre de tu empresa. Todos los derechos reservados.<br>
      <a href="https://www.tusitio.com/desuscribirse">Cancelar suscripción</a>
    </div>
  </div>
</body>
</html>