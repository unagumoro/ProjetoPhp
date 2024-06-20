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
        $q = "SELECT cod, usuario, nome, senha FROM usuarios WHERE usuario='$usuario'";
        $busca = Banco::query($q);

        if($busca->num_rows > 0){
            $usu = $busca->fetch_object();
            if(password_verify($senha, $usu->senha)){
                $resp = "Login :)";
                if(session_id() == '') {
                    session_start();
                    $_SESSION["user"] = $usuario;
                    $_SESSION["user_id"] = $usu->cod;
                }
                return true;
            } else {
                $resp = "Senha Inválida :/";
                return false;
            }
        }
        return false;
    }

    function criarUsuario(string $usuario, string $nome, string $senha, $debug = true) : void {
        $tipo = ($usuario === 'admin' && $nome === 'admin' && $senha === 'admin') ? 'admin' : 'cliente';
        $senha = password_hash($senha, PASSWORD_DEFAULT);
        $q = "INSERT INTO usuarios(cod, usuario, nome, senha, tipo) VALUES (NULL, '$usuario', '$nome', '$senha', '$tipo')";
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
?>