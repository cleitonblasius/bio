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
            /* Fundo padrão Bootstrap */
            /* background-image: linear-gradient(0deg, lightgreen, white); */
        }

        .login-container {
            background-color: #ffffff;
            /* Branco para contraste */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Sombra adicionada */
            width: 33.3333%;
            text-align: center;
        }

        .login-container h1 {
            color: #0d6efd;
            /* Cor primária do Bootstrap */
            margin-bottom: 20px;
        }

        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ced4da;
            /* Bordas padrão do Bootstrap */
            border-radius: 5px;
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #0d6efd;
            /* Botão primário Bootstrap */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .login-container button:hover {
            background-color: #0b5ed7;
            /* Tom mais escuro ao passar o mouse */
        }
    </style>
</head>

<body>
    <div class="login-container">
        <img src="./image/logo.png" style="width: 25vh;">
        <form action="inicio.php" method="post">
            <input type="text" name="username" placeholder="Usuário" required>
            <input type="password" name="password" placeholder="Senha" required>
            <button id="btnLogin" type="submit" class="btn btn-primary">Entrar</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        //$('#btnLogin').click(window.location.href = './inicio.php');
        //document.getElementById('btnLogin').onclick(window.location.href = './inicio.php');
    </script>
</body>

</html>