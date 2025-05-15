<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #3490dc;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            color: #333;
        }
        .success {
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border-radius: 4px;
            margin-top: 20px;
        }
        .logo {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #3490dc;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">TechConnect</div>
        <h1>{{ $titulo }}</h1>
        <div class="success">
            <p>{{ $mensaje }}</p>
        </div>
        <p>Esta es la ruta de prueba para la primera entrega del proyecto.</p>
    </div>
</body>
</html>
