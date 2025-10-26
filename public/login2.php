<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

        .login-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 33.3333%;
            text-align: center;
        }

        /* Input container */
        .input-container {
            position: relative;
            margin: 20px;
        }

        /* Input field */
        .input-field {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-bottom: 2px solid #ccc;
            outline: none;
            background-color: transparent;
        }

        /* Input label */
        .input-label {
            position: absolute;
            top: 0;
            left: 0;
            font-size: 16px;
            color: rgba(204, 204, 204, 0);
            pointer-events: none;
            transition: all 0.3s ease;
        }

        /* Input highlight */
        .input-highlight {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 2px;
            width: 0;
            background-color: #157347;
            transition: all 0.3s ease;
        }

        /* Input field:focus styles */
        .input-field:focus+.input-label {
            top: -20px;
            font-size: 12px;
            color: #157347;
        }

        .input-field:focus+.input-label+.input-highlight {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <img src="./image/logo.png" style="width: 25vh;">
        <form action="inicio.php" method="post">
            <div class="input-container">
                <input class="input-field" type="text" name="username" placeholder="Usuário" required>
                <label for="input-field" class="input-label">Usuário</label>
                <span class="input-highlight"></span>
            </div>
            <div class="input-container">
                <input placeholder="Senha" class="input-field" type="password" name="password" required>
                <label for="input-field" class="input-label">Senha</label>
                <span class="input-highlight"></span>
            </div>
            <button id="btnLogin" type="submit" class="btn btn-success float-end me-4">Entrar</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        //$('#btnLogin').click(window.location.href = './inicio.php');
        //document.getElementById('btnLogin').onclick(window.location.href = './inicio.php');
    </script>
</body>

</html>