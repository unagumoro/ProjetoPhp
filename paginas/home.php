<?php
session_start();
include_once 'banco.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'];

    switch ($acao) {
        case 'salvar_palpite':
            for ($i = 1; $i <= 12; $i += 2) {
                $placar1 = (int) $_POST['placar' . $i];
                $placar2 = (int) $_POST['placar' . ($i + 1)];
                $jogo_id = ceil($i / 2);

                // Salvar palpite no banco de dados
                $q = "INSERT INTO palpites (jogo_id, placar_time1, placar_time2) VALUES (?, ?, ?)";
                $stmt = Banco::Instance()->banco->prepare($q);
                $stmt->bind_param('iii', $jogo_id, $placar1, $placar2);
                $stmt->execute();
            }
            break;

        case 'atualizar_resultado':
            for ($i = 1; $i <= 12; $i += 2) {
                $placar1 = (int) $_POST['placar' . $i];
                $placar2 = (int) $_POST['placar' . ($i + 1)];
                $jogo_id = ceil($i / 2);

                // Atualizar resultado no banco de dados
                $q = "INSERT INTO resultados (jogo_id, placar_time1, placar_time2) VALUES (?, ?, ?)";
                $stmt = Banco::Instance()->banco->prepare($q);
                $stmt->bind_param('iii', $placar1, $placar2, $jogo_id);
                $stmt->execute();
            }
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Palpites de Futebol</title>
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Young+Serif&display=swap" rel="stylesheet">
<style>
    table, th, td {
        border: 1px solid black;
    }
    body {
        font-family: Arial, sans-serif;
        text-align: center;
        background-color: green;
        background-repeat: repeat;
    }
    .container {
        margin: 20px auto;
        padding: 20px;
        width: 80%;
        max-width: 1200px;
    }
    .table-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        margin: 20px 0;
    }
    .table-group {
        border: 7px solid blue;
        background-color: yellow;
        padding: 10px 20px; /* Reduz a distância em cima e embaixo */
        margin: 10px;
        flex: 1 1 45%;
        box-sizing: border-box;
    }
    .table-group p {
        margin: 0; /* Remove a margem padrão do parágrafo */
        margin-bottom: 10px; /* Adiciona uma nova margem embaixo do parágrafo */
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 20px;
    }
    input[type="text"] {
        width: 50px;
        padding: 5px;
        text-align: center;
        margin: 0 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    button {
        padding: 10px 20px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 20px;
    }
    button:hover {
        background-color: #0056b3;
    }
</style>
</head>
<body>
<div class="container">
    <p style="font-family: Young Serif; color:blue; font-size: 40px;">PALPITES</p>
    <form action="home.php" method="post">
        <div class="table-container">
            <div class="table-group">
                <p style="font-family: Permanent Marker; color:black; font-size: 45px; margin-top: 5px;">Grupo A</p>
                <table style="margin-top: 20px;">
                    <tr>
                        <th>CLASSIFICAÇÃO</th>
                        <th>P</th>
                        <th>J</th>
                        <th>V</th>
                        <th>E</th>
                        <th>D</th>
                        <th>GP</th>
                        <th>GC</th>
                        <th>SG</th>
                    </tr>
                    <tr><td>Argentina</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
                    <tr><td>Canadá</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
                    <tr><td>Chile</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
                    <tr><td>Peru</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
                </table>
                <br>
                <table>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id1" value="1">
                            <label for="placar1">Argentina</label>
                            <input type="text" name="placar1" id="placar1" required> 
                            X 
                            <label for="placar2">Canadá</label>
                            <input type="text" name="placar2" id="placar2" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id2" value="2">
                            <label for="placar3">Peru</label>
                            <input type="text" name="placar3" id="placar3" required> 
                            X 
                            <label for="placar4">Chile</label>
                            <input type="text" name="placar4" id="placar4" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id3" value="3">
                            <label for="placar5">Peru</label>
                            <input type="text" name="placar5" id="placar5" required> 
                            X 
                            <label for="placar6">Canadá</label>
                            <input type="text" name="placar6" id="placar6" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id4" value="4">
                            <label for="placar7">Chile</label>
                            <input type="text" name="placar7" id="placar7" required> 
                            X 
                            <label for="placar8">Argentina</label>
                            <input type="text" name="placar8" id="placar8" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id5" value="5">
                            <label for="placar9">Argentina</label>
                            <input type="text" name="placar9" id="placar9" required> 
                            X 
                            <label for="placar10">Peru</label>
                            <input type="text" name="placar10" id="placar10" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id6" value="6">
                            <label for="placar11">Canadá</label>
                            <input type="text" name="placar11" id="placar11" required> 
                            X 
                            <label for="placar12">Chile</label>
                            <input type="text" name="placar12" id="placar12" required>
                        </td>
                    </tr>
                </table>
                <button type="submit" name="acao" value="salvar_palpite">Salvar Palpites</button>
                <?php
                if(isset($_SESSION['user']) && $_SESSION['user'] === 'admin') {
                    echo '<button type="submit" name="acao" value="atualizar_resultado">Atualizar Resultados</button>';
                }
                ?> 
            </div>
            <div class="table-group">
                <p style="font-family: Permanent Marker; color:black; font-size: 45px; margin-top: 5px;">Grupo B</p>
                <table style="margin-top: 20px;">
                    <tr>
                        <th>CLASSIFICAÇÃO</th>
                        <th>P</th>
                        <th>J</th>
                        <th>V</th>
                        <th>E</th>
                        <th>D</th>
                        <th>GP</th>
                        <th>GC</th>
                        <th>SG</th>
                    </tr>
                    <tr><td>Equador</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
                    <tr><td>Jamaica</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
                    <tr><td>México</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
                    <tr><td>Venezuela</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
                </table>
                <br>
                <table>
                    <tr><td>Equador <input type="text" name="brasil_B" placeholder="0"> X <input type="text" name="argentina_B" placeholder="0"> Venezuela</td></tr>
                    <tr><td>México <input type="text" name="alemanha_B" placeholder="0"> X <input type="text" name="franca_B" placeholder="0"> Jamaica</td></tr>
                    <tr><td>Ecuador <input type="text" name="espanha_B" placeholder="0"> X <input type="text" name="italia_B" placeholder="0"> Jamaica</td></tr>
                    <tr><td>Venezuela <input type="text" name="inglaterra_B" placeholder="0"> X <input type="text" name="holanda_B" placeholder="0"> México</td></tr>
                    <tr><td>México <input type="text" name="portugal_B" placeholder="0"> X <input type="text" name="belgica_B" placeholder="0"> Equador</td></tr>
                    <tr><td>Jamaica <input type="text" name="uruguai_B" placeholder="0"> X <input type="text" name="chile_B" placeholder="0"> Venezuela</td></tr>
                </table>
                <button type="submit" name="grupo" value="B">Salvar Palpites</button>
            </div>
            <div class="table-group">
                <p style="font-family: Permanent Marker; color:black; font-size: 45px; margin-top: 5px;">Grupo C</p>
                <table style="margin-top: 20px;">
                    <tr>
                        <th>CLASSIFICAÇÃO</th>
                        <th>P</th>
                        <th>J</th>
                        <th>V</th>
                        <th>E</th>
                        <th>D</th>
                        <th>GP</th>
                        <th>GC</th>
                        <th>SG</th>
                    </tr>
                    <tr><td>Bolívia</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
                    <tr><td>Estados Unidos</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
                    <tr><td>Panamá</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
                    <tr><td>Uruguai</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
                </table>
                <br>
                <table>
                    <tr><td>Estados Unidos <input type="text" name="brasil_C" placeholder="0"> X <input type="text" name="argentina_C" placeholder="0"> Bolívia</td></tr>
                    <tr><td>Uruguai <input type="text" name="alemanha_C" placeholder="0"> X <input type="text" name="franca_C" placeholder="0"> Panamá</td></tr>
                    <tr><td>Panamá <input type="text" name="espanha_C" placeholder="0"> X <input type="text" name="italia_C" placeholder="0"> Estados Unidos</td></tr>
                    <tr><td>Uruguai <input type="text" name="inglaterra_C" placeholder="0"> X <input type="text" name="holanda_C" placeholder="0"> Bolívia</td></tr>
                    <tr><td>Estados Unidos <input type="text" name="portugal_C" placeholder="0"> X <input type="text" name="belgica_C" placeholder="0"> Uruguai</td></tr>
                    <tr><td>Bolívia <input type="text" name="uruguai_C" placeholder="0"> X <input type="text" name="chile_C" placeholder="0"> Panamá</td></tr>
                </table>
                <button type="submit" name="grupo" value="C">Salvar Palpites</button>
            </div>
            <div class="table-group">
                <p style="font-family: Permanent Marker; color:black; font-size: 45px; margin-top: 5px;">Grupo D</p>
                <table style="margin-top: 20px;">
                    <tr>
                        <th>CLASSIFICAÇÃO</th>
                        <th>P</th>
                        <th>J</th>
                        <th>V</th>
                        <th>E</th>
                        <th>D</th>
                        <th>GP</th>
                        <th>GC</th>
                        <th>SG</th>
                    </tr>
                    <tr><td>Brasil</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
                    <tr><td>Colômbia</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
                    <tr><td>Costa Rica</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
                    <tr><td>Paraguai</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
                </table>
                <br>
                <table>
                    <tr><td>Colômbia <input type="text" name="brasil_D" placeholder="0"> X <input type="text" name="argentina_D" placeholder="0"> Paraguai</td></tr>
                    <tr><td>Brasil <input type="text" name="alemanha_D" placeholder="0"> X <input type="text" name="franca_D" placeholder="0"> Costa Rica</td></tr>
                    <tr><td>Colômbia <input type="text" name="espanha_D" placeholder="0"> X <input type="text" name="italia_D" placeholder="0"> Costa Rica</td></tr>
                    <tr><td>Paraguai <input type="text" name="inglaterra_D" placeholder="0"> X <input type="text" name="holanda_D" placeholder="0"> Brasil</td></tr>
                    <tr><td>Brasil <input type="text" name="portugal_D" placeholder="0"> X <input type="text" name="belgica_D" placeholder="0"> Colômbia</td></tr>
                    <tr><td>Costa Rica <input type="text" name="uruguai_D" placeholder="0"> X <input type="text" name="chile_D" placeholder="0"> Paraguai</td></tr>
                </table>
                <button type="submit" name="grupo" value="D">Salvar Palpites</button>
            </div>
            <div class="table-group">
                <p style="font-family: Permanent Marker; color:black; font-size: 45px;">Quartas de Final</p>
                <table>
                    <tr><td>Time A <input type="text" name="quartas_A" placeholder="0"> X <input type="text" name="quartas_B" placeholder="0"> Time B</td></tr>
                    <tr><td>Time C <input type="text" name="quartas_C" placeholder="0"> X <input type="text" name="quartas_D" placeholder="0"> Time D</td></tr>
                    <tr><td>Time E <input type="text" name="quartas_E" placeholder="0"> X <input type="text" name="quartas_F" placeholder="0"> Time F</td></tr>
                    <tr><td>Time G <input type="text" name="quartas_G" placeholder="0"> X <input type="text" name="quartas_H" placeholder="0"> Time H</td></tr>
                </table>
                <button type="submit" name="fase" value="quartas">Salvar Palpites</button>
            </div>
            <div class="table-group">
                <p style="font-family: Permanent Marker; color:black; font-size: 45px;">Semi-Final</p>
                <table>
                    <tr><td>Time A <input type="text" name="semis_A" placeholder="0"> X <input type="text" name="semis_B" placeholder="0"> Time B</td></tr>
                    <tr><td>Time C <input type="text" name="semis_C" placeholder="0"> X <input type="text" name="semis_D" placeholder="0"> Time D</td></tr>
                </table>
                <button type="submit" name="fase" value="semis">Salvar Palpites</button>
            </div>
            <div class="table-group">
                <p style="font-family: Permanent Marker; color:black; font-size: 45px;">3º Lugar</p>
                <table>
                    <tr><td>Time A <input type="text" name="3lugar_A" placeholder="0"> X <input type="text" name="3lugar_B" placeholder="0"> Time B</td></tr>
                </table>
                <button type="submit" name="fase" value="semis">Salvar Palpites</button>
            </div>
            <div class="table-group">
                <p style="font-family: Permanent Marker; color:black; font-size: 45px;">Final</p>
                <table>
                    <tr><td>Time A <input type="text" name="final_A" placeholder="0"> X <input type="text" name="final_B" placeholder="0"> Time B</td></tr>
                </table>
                <button type="submit" name="fase" value="semis">Salvar Palpites</button>
            </div>
        </div>
    </form>
</div>
</body>
</html>
