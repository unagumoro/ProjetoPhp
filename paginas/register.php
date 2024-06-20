<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
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
        .container input {
            width: 90%; /* Ajuste a largura conforme necessário */
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
        <h1>Cadastro</h1>
        <form method="post" action="register.php">
            <input type="text" name="usuario" placeholder="Usuário" required>
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Cadastrar</button>
        </form>
        <a href="login.php">Fazer Login</a> <!-- Link para a página de login -->
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            include 'banco.php';

            $usuario = $_POST['usuario'];
            $nome = $_POST['nome'];
            $senha = $_POST['senha'];

            $banco = Banco::Instance();
            $banco->criarUsuario($usuario, $nome, $senha);

            echo "<p style='color: green;'>Cadastro realizado com sucesso!</p>";
        }
        ?>
    </div>
</body>
</html>
