<?php
session_start();
include_once 'banco.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    if (isset($_SESSION["usuario"])) {
        $usuario = $_SESSION["usuario"];
        $acao = $_POST['acao'];

        switch ($acao) {
            case 'salvar_palpites':
                $nome = $_POST['nome'];
                $palpites = [];
                
                for ($i = 1; $i <= 64; $i += 2) {
                    $placar1 = (int)$_POST['placar' . $i];
                    $placar2 = (int)$_POST['placar' . ($i + 1)];
                    $jogo_id = ceil($i / 2);
                
                    $palpites[] = [
                        'nome' => $nome,
                        'jogo_id' => $jogo_id,
                        'placar_time1' => $placar1,
                        'placar_time2' => $placar2
                    ];
                }
                
                foreach ($palpites as $palpite) {
                    $q = "INSERT INTO palpites (nome, jogo_id, placar_time1, placar_time2) VALUES (?, ?, ?, ?)";
                    $stmt = Banco::Instance()->banco->prepare($q);
                    $stmt->bind_param('siii', $palpite['nome'], $palpite['jogo_id'], $palpite['placar_time1'], $palpite['placar_time2']);
                    $stmt->execute();
                }
                break;            

            case 'atualizar_resultado':
                for ($i = 1; $i <= 64; $i += 2) {
                    $placar1 = (int) $_POST['placar' . $i];
                    $placar2 = (int) $_POST['placar' . ($i + 1)];
                    $jogo_id = ceil($i / 2);

                    $q_check = "SELECT COUNT(*) AS total FROM resultados WHERE jogo_id = ?";
                    $stmt_check = Banco::Instance()->banco->prepare($q_check);
                    $stmt_check->bind_param('i', $jogo_id);
                    $stmt_check->execute();
                    $result = $stmt_check->get_result();
                    $row = $result->fetch_assoc();
            
                    if ($row['total'] > 0) {
                        $q = "UPDATE resultados SET placar_time1 = ?, placar_time2 = ? WHERE jogo_id = ?";
                        $stmt = Banco::Instance()->banco->prepare($q);
                        $stmt->bind_param('iii', $placar1, $placar2, $jogo_id);
                    } else {
                        $q = "INSERT INTO resultados (jogo_id, placar_time1, placar_time2) VALUES (?, ?, ?)";
                        $stmt = Banco::Instance()->banco->prepare($q);
                        $stmt->bind_param('iii', $jogo_id, $placar1, $placar2);
                    }
            
                    $stmt->execute();
                }
                break;
        }
    } else {
        header('Location: login.php');
        exit();
    }
}

$q_ranking = "
SELECT p.nome, COUNT(*) AS acertos
FROM palpites p
JOIN resultados r ON p.jogo_id = r.jogo_id AND p.placar_time1 = r.placar_time1 AND p.placar_time2 = r.placar_time2
GROUP BY p.nome
ORDER BY acertos DESC;
";

$stmt_ranking = Banco::Instance()->banco->query($q_ranking);

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
        padding: 10px 20px;
        margin: 10px;
        flex: 1 1 45%;
        box-sizing: border-box;
    }
    .table-group p {
        margin: 0;
        margin-bottom: 10px;
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
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id7" value="7">
                            <label for="placar13">Equador</label>
                            <input type="text" name="placar13" id="placar13" required> 
                            X 
                            <label for="placar14">Venezuela</label>
                            <input type="text" name="placar14" id="placar14" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id8" value="8">
                            <label for="placar15">México</label>
                            <input type="text" name="placar15" id="placar15" required> 
                            X 
                            <label for="placar16">Jamaica</label>
                            <input type="text" name="placar16" id="placar16" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id9" value="9">
                            <label for="placar17">Equador</label>
                            <input type="text" name="placar17" id="placar17" required> 
                            X 
                            <label for="placar18">Jamaica</label>
                            <input type="text" name="placar18" id="placar18" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id10" value="10">
                            <label for="placar19">Venezuela</label>
                            <input type="text" name="placar19" id="placar19" required> 
                            X 
                            <label for="placar20">México</label>
                            <input type="text" name="placar20" id="placar20" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id11" value="11">
                            <label for="placar21">México</label>
                            <input type="text" name="placar21" id="placar21" required> 
                            X 
                            <label for="placar22">Equador</label>
                            <input type="text" name="placar22" id="placar22" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id12" value="12">
                            <label for="placar23">Jamaica</label>
                            <input type="text" name="placar23" id="placar23" required> 
                            X 
                            <label for="placar24">Venezuela</label>
                            <input type="text" name="placar24" id="placar24" required>
                        </td>
                    </tr>
                </table>
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
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id13" value="13">
                            <label for="placar25">Estados Unidos</label>
                            <input type="text" name="placar25" id="placar25" required> 
                            X 
                            <label for="placar26">Bolívia</label>
                            <input type="text" name="placar26" id="placar26" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id14" value="14">
                            <label for="placar27">Uruguai</label>
                            <input type="text" name="placar27" id="placar27" required> 
                            X 
                            <label for="placar28">Panamá</label>
                            <input type="text" name="placar28" id="placar28" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id15" value="15">
                            <label for="placar29">Panamá</label>
                            <input type="text" name="placar29" id="placar29" required> 
                            X 
                            <label for="placar30">Estados Unidos</label>
                            <input type="text" name="placar30" id="placar30" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id16" value="16">
                            <label for="placar31">Uruguai</label>
                            <input type="text" name="placar31" id="placar31" required> 
                            X 
                            <label for="placar32">Bolívia</label>
                            <input type="text" name="placar32" id="placar32" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id17" value="17">
                            <label for="placar33">Estados Unidos</label>
                            <input type="text" name="placar33" id="placar33" required> 
                            X 
                            <label for="placar34">Uruguai</label>
                            <input type="text" name="placar34" id="placar34" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id18" value="18">
                            <label for="placar35">Bolívia</label>
                            <input type="text" name="placar35" id="placar35" required> 
                            X 
                            <label for="placar36">Panamá</label>
                            <input type="text" name="placar36" id="placar36" required>
                        </td>
                    </tr>
                </table>
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
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id19" value="19">
                            <label for="placar37">Colômbia</label>
                            <input type="text" name="placar37" id="placar37" required> 
                            X 
                            <label for="placar19">Paraguai</label>
                            <input type="text" name="placar38" id="placar38" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id20" value="20">
                            <label for="placar39">Brasil</label>
                            <input type="text" name="placar39" id="placar39" required> 
                            X 
                            <label for="placar40">Costa Rica</label>
                            <input type="text" name="placar40" id="placar40" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id21" value="21">
                            <label for="placar41">Colômbia</label>
                            <input type="text" name="placar41" id="placar41" required> 
                            X 
                            <label for="placar42">Costa Rica</label>
                            <input type="text" name="placar42" id="placar42" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id22" value="22">
                            <label for="placar22">Paraguai</label>
                            <input type="text" name="placar43" id="placar43" required> 
                            X 
                            <label for="placar44">Brasil</label>
                            <input type="text" name="placar44" id="placar44" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id23" value="23">
                            <label for="placar45">Brasil</label>
                            <input type="text" name="placar45" id="placar45" required> 
                            X 
                            <label for="placar46">Colômbia</label>
                            <input type="text" name="placar46" id="placar46" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id24" value="24">
                            <label for="placar47">Costa Rica</label>
                            <input type="text" name="placar47" id="placar47" required> 
                            X 
                            <label for="placar48">Paraguai</label>
                            <input type="text" name="placar48" id="placar48" required>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="table-group">
                <p style="font-family: Permanent Marker; color:black; font-size: 45px;">Quartas de Final</p>
                <table>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id25" value="25">
                            <label for="placar49">A</label>
                            <input type="text" name="placar49" id="placar49" required> 
                            X 
                            <label for="placar50">B</label>
                            <input type="text" name="placar50" id="placar50" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id26" value="26">
                            <label for="placar51">A</label>
                            <input type="text" name="placar51" id="placar51" required> 
                            X 
                            <label for="placar52">B</label>
                            <input type="text" name="placar52" id="placar52" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id27" value="27">
                            <label for="placar53">A</label>
                            <input type="text" name="placar53" id="placar53" required> 
                            X 
                            <label for="placar54">B</label>
                            <input type="text" name="placar54" id="placar54" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id28" value="28">
                            <label for="placar55">A</label>
                            <input type="text" name="placar55" id="placar55" required> 
                            X 
                            <label for="placar56">B</label>
                            <input type="text" name="placar56" id="placar56" required>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="table-group">
                <p style="font-family: Permanent Marker; color:black; font-size: 45px;">Semi-Final</p>
                <table>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id29" value="29">
                            <label for="placar57">A</label>
                            <input type="text" name="placar57" id="placar57" required> 
                            X 
                            <label for="placar58">B</label>
                            <input type="text" name="placar58" id="placar58" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id30" value="30">
                            <label for="placar59">A</label>
                            <input type="text" name="placar59" id="placar59" required> 
                            X 
                            <label for="placar62">B</label>
                            <input type="text" name="placar60" id="placar60" required>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="table-group">
                <p style="font-family: Permanent Marker; color:black; font-size: 45px;">3º Lugar</p>
                <table>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id31" value="31">
                            <label for="placar61">A</label>
                            <input type="text" name="placar61" id="placar61" required> 
                            X 
                            <label for="placar62">B</label>
                            <input type="text" name="placar62" id="placar62" required>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="table-group">
                <p style="font-family: Permanent Marker; color:black; font-size: 45px;">Final</p>
                <table>
                    <tr>
                        <td>
                            <input type="hidden" name="jogo_id7" value="7">
                            <label for="placar13">A</label>
                            <input type="text" name="placar13" id="placar13" required> 
                            X 
                            <label for="placar64">B</label>
                            <input type="text" name="placar14" id="placar14" required>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="table-group">
                <p style="font-family: Permanent Marker; color:black; font-size: 45px;">Salvar Palpites</p>
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" style="width: 200px;" required>
                <button type="submit" name="acao" value="salvar_palpites">Salvar</button>
                <?php
                if(isset($_SESSION['usuario']) && $_SESSION['usuario'] === 'admin') {
                    echo '<button type="submit" name="acao" value="atualizar_resultado">Atualizar Resultados</button>';
                }
                ?>
            </div>
            <div class="table-group">
                <p style="font-family: Permanent Marker; color:black; font-size: 45px;">Ranking de Acertos</p>
                <table class="styled-table">
                    <tr><th>Nome</th><th>Acertos</th></tr>
                    <?php while ($row = $stmt_ranking->fetch_assoc()) { ?>
                        <tr><td><?php echo $row['nome']; ?></td><td><?php echo $row['acertos']; ?></td></tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </form>
</div>
</body>
</html>
