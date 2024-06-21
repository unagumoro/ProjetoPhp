<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_POST["delete"])) {
    include 'banco.php';
    $banco = Banco::Instance();
    $banco->deletarUsuario($_SESSION["usuario"]);
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <style>
        body {
            font-family: 'Permanent Marker', cursive;
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
        .container p {
            margin-bottom: 10px;
        }
        .container button {
            width: 100%;
            padding: 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        .container button:hover {
            background-color: #c82333;
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
        <h1>Perfil do Usuário</h1>
        <p>Bem-vindo, <?php echo $_SESSION["usuario"]; ?>!</p>
        <p>Aqui você pode gerenciar suas informações.</p>
        <form method="post" action="profile.php">
            <button type="submit" name="delete">Deletar Usuário</button>
            <button type="submit" name="logout">Deslogar</button>
        </form>
        <a href="home.php">Página Principal</a>
    </div>
</body>
</html>
