<?php
session_start();

if (isset($_SESSION["usuario"])) {
    header("Location: profile.php");
    exit();
}

include 'banco.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $banco = Banco::Instance();
    if ($banco->fazerLogin($usuario, $senha)) {
        $_SESSION["usuario"] = $usuario;
        header("Location: profile.php");
        exit();
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Permanent Marker', cursive;
            color: black;
            text-align: center;
            background-color: green;
            background-repeat: repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: yellow;
            border: 7px solid blue;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .container h1 {
            margin-bottom: 20px;
            color: #333;
        }
        .container input {
            width: 95%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .container button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .container button:hover {
            background-color: #0056b3;
        }
        .container a {
            display: block;
            margin-top: 20px;
            color: #007BFF;
            text-decoration: none;
        }
        .container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form method="post" action="login.php">
            <input type="text" name="usuario" placeholder="Usuário" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <a href="register.php">Ainda não tenho cadastro</a>
        <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
    </div>
</body>
</html>
