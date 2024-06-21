<?php
class Banco {
    private static $instance;
    public $banco;
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $database = "banco123";
    
    function __construct() {
    }

    public static function Instance() {
        if(self::$instance === null){
            self::$instance = new self;
            self::$instance->banco = new mysqli(self::$instance->host, self::$instance->user, self::$instance->password, self::$instance->database);    
        }
        return self::$instance;
    }
    
    static function query($q, $debug = true) : object | bool {
        $r = self::Instance()->banco->query($q);
        return $r;
    }

    public function fazerLogin(string $usuario, string $senha) : bool {
        $q = "SELECT usuario, nome, senha FROM usuarios WHERE usuario='$usuario'";
        $busca = Banco::query($q);

        if($busca->num_rows > 0){
            $usu = $busca->fetch_object();
            if(password_verify($senha, $usu->senha)){
                $r = "Login :)";
                if(session_id() == '') {
                    session_start();
                    $_SESSION["usuario"] = $usuario;
                }
                return true;
            } else {
                $r = "Senha Inválida :/";
                return false;
            }
        }
        return false;
    }

    function criarUsuario(string $usuario, string $nome, string $senha, $debug = true) : void {
        $senha = password_hash($senha, PASSWORD_DEFAULT);
        $q = "INSERT INTO usuarios(usuario, nome, senha) VALUES ('$usuario', '$nome', '$senha')";
        $r = Banco::query($q);
    }

    function deletarUsuario(string $usuario, $debug = true) : void {
        $q = "DELETE FROM usuarios WHERE usuario='$usuario'";
        $r = Banco::query($q);
    }
    
    function atualizarUsuario(string $usuario, string $nome="", string $senha="", bool $debug = true) : void {
        $set = "";
        if($nome != "" && $senha != ""){
            $novaSenha = password_hash($senha, PASSWORD_DEFAULT);
            $set = "nome='$nome', senha='$novaSenha'";
        } else if($nome != ""){
            $set = "nome='$nome'";
        } else if ($senha != ""){
            $novaSenha = password_hash($senha, PASSWORD_DEFAULT);
            $set = "senha='$novaSenha'";
        }
        
        $q = "UPDATE usuarios SET $set WHERE usuario='$usuario'";
        $r = Banco::query($q);
    }

    static function prepare($q, $debug = true) : object | bool {
        $stmt = self::Instance()->banco->prepare($q);
        if (!$stmt) {
            if ($debug) {
                echo "Prepare failed: (" . self::Instance()->banco->errno . ") " . self::Instance()->banco->error;
            }
            return false;
        }
        return $stmt;
    }
    
    static function execute($stmt, $debug = true) : object | bool {
        if (!$stmt->execute()) {
            if ($debug) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }
            return false;
        }
        return $stmt->get_result();
    }

    static function bindParams($stmt, $types, ...$params) : void {
        $stmt->bind_param($types, ...$params);
    }
}

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
                    $time1 = '';
                    $time2 = '';

                    if ($jogo_id == 1) {
                        $time1 = 'Argentina';
                        $time2 = 'Canadá';
                    } elseif ($jogo_id == 2) {
                        $time1 = 'Peru';
                        $time2 = 'Chile';
                    } elseif ($jogo_id == 3) {
                        $time1 = 'Peru';
                        $time2 = 'Canadá';
                    } elseif ($jogo_id == 4) {
                        $time1 = 'Chile';
                        $time2 = 'Argentina';
                    } elseif ($jogo_id == 5) {
                        $time1 = 'Argentina';
                        $time2 = 'Peru';
                    } elseif ($jogo_id == 6) {
                        $time1 = 'Canadá';
                        $time2 = 'Chile';
                    } elseif ($jogo_id == 7) {
                        $time1 = 'Equador';
                        $time2 = 'Jamaica';
                    } elseif ($jogo_id == 8) {
                        $time1 = 'México';
                        $time2 = 'Venezuela';
                    } elseif ($jogo_id == 9) {
                        $time1 = 'Equador';
                        $time2 = 'Jamaica';
                    } elseif ($jogo_id == 10) {
                        $time1 = 'Venezuela';
                        $time2 = 'México';
                    } elseif ($jogo_id == 11) {
                        $time1 = 'México';
                        $time2 = 'Equador';
                    } elseif ($jogo_id == 12) {
                        $time1 = 'Jamaica';
                        $time2 = 'Venezuela';
                    } elseif ($jogo_id == 13) {
                        $time1 = 'Estados Unidos';
                        $time2 = 'Bolívia';
                    } elseif ($jogo_id == 14) {
                        $time1 = 'Uruguai';
                        $time2 = 'Panamá';
                    } elseif ($jogo_id == 15) {
                        $time1 = 'Panamá';
                        $time2 = 'Estados Unidos';
                    } elseif ($jogo_id == 16) {
                        $time1 = 'Uruguai';
                        $time2 = 'Bolívia';
                    } elseif ($jogo_id == 17) {
                        $time1 = 'Estados Unidos';
                        $time2 = 'Uruguai';
                    } elseif ($jogo_id == 18) {
                        $time1 = 'Bolívia';
                        $time2 = 'Panamá';
                    } elseif ($jogo_id == 19) {
                        $time1 = 'Colômbia';
                        $time2 = 'Paraguai';
                    } elseif ($jogo_id == 20) {
                        $time1 = 'Brasil';
                        $time2 = 'Costa Rica';
                    } elseif ($jogo_id == 21) {
                        $time1 = 'Colômbia';
                        $time2 = 'Costa Rica';
                    } elseif ($jogo_id == 22) {
                        $time1 = 'Paraguai';
                        $time2 = 'Brasil';
                    } elseif ($jogo_id == 23) {
                        $time1 = 'Brasil';
                        $time2 = 'Colômbia';
                    } elseif ($jogo_id == 24) {
                        $time1 = 'Costa Rica';
                        $time2 = 'Paraguai';
                    } elseif ($jogo_id == 25) {
                        $time1 = 'A';
                        $time2 = 'B';
                    } elseif ($jogo_id == 26) {
                        $time1 = 'A';
                        $time2 = 'B';
                    } elseif ($jogo_id == 27) {
                        $time1 = 'A';
                        $time2 = 'B';
                    } elseif ($jogo_id == 28) {
                        $time1 = 'A';
                        $time2 = 'B';
                    } elseif ($jogo_id == 29) {
                        $time1 = 'A';
                        $time2 = 'B';
                    } elseif ($jogo_id == 30) {
                        $time1 = 'A';
                        $time2 = 'B';
                    } elseif ($jogo_id == 31) {
                        $time1 = 'A';
                        $time2 = 'B';
                    } elseif ($jogo_id == 32) {
                        $time1 = 'A';
                        $time2 = 'B';
                    }

                    $q_check = "SELECT COUNT(*) AS total FROM resultados WHERE jogo_id = ?";
                    $stmt_check = Banco::Instance()->banco->prepare($q_check);
                    $stmt_check->bind_param('i', $jogo_id);
                    $stmt_check->execute();
                    $result = $stmt_check->get_result();
                    $row = $result->fetch_assoc();
            
                    if ($row['total'] > 0) {
                        $q = "UPDATE resultados SET placar_time1 = ?, placar_time2 = ?, time1 = ?, time2 = ? WHERE jogo_id = ?";
                        $stmt = Banco::Instance()->banco->prepare($q);
                        $stmt->bind_param('iissi', $placar1, $placar2, $time1, $time2, $jogo_id);
                    } else {
                        $q = "INSERT INTO resultados (jogo_id, placar_time1, placar_time2, time1, time2) VALUES (?, ?, ?, ?, ?)";
                        $stmt = Banco::Instance()->banco->prepare($q);
                        $stmt->bind_param('iiiis', $jogo_id, $placar1, $placar2, $time1, $time2);
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
?>