
<?php
require "config.php";

header("Content-Type: application/json; charset=UTF-8");
$rota = $_GET["rota"] ?? "";
$metodo = $_SERVER["REQUEST_METHOD"];
$corpo = json_decode(file_get_contents("php://input"), true) ?? [];

function teste(){
    echo json_encode([
        "mensagem" => "Back-end do Instagram respondendo!"]);
    exit;
}
function createUsuarios($con, $dados){$stmt = $con->prepare("INSERT INTO USUARIOS (NOME, USERNAME, EMAIL, SENHA)VALUES (?, ?, ?, ?)");

    $stmt->execute([$dados["nome"], $dados["username"], $dados["email"], $dados["senha"]]);
    http_response_code(201);
    echo json_encode([
        "mensagem" => "Usuario criado com sucesso!",
        "id" => $con->lastInsertId()]);
}
function createPublicacoes($con, $dados){
    $stmt = $con->prepare( "INSERT INTO PUBLICACOES (ID_USUARIO, LEGENDA, IMAGEM_URL) VALUES (?, ?, ?)");
    $stmt->execute([$dados["id_usuario"],$dados["legenda"],$dados["imagem_url"]]);

    http_response_code(201);
    echo json_encode(["mensagem" => "Publicacao postada com sucesso!","id" => $con->lastInsertId()]);
}

function createCurtidas($con, $dados){
    $stmt = $con->prepare(
        "INSERT INTO CURTIDAS (ID_USUARIO, ID_PUBLICACAO)VALUES (?, ?)");
    $stmt->execute([$dados["id_usuario"],$dados["id_publicacao"]]);

    http_response_code(201);
    echo json_encode(["mensagem" => "Curtida enviada com sucesso!","id" => $con->lastInsertId()]);
}
function readUsuarios($con){
    $stmt = $con->query("SELECT * FROM USUARIOS");

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function readPublicacoes($con){
    $stmt = $con->query("SELECT * FROM PUBLICACOES");

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function readCurtidas($con){
    $stmt = $con->query("SELECT * FROM CURTIDAS");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($rota === "usuarios"){
    switch ($metodo){
        case "GET":
            readUsuarios($con);
            break;
        case "POST":
            createUsuarios($con, $corpo);
            break;
        default:
            teste();
    }
}
elseif ($rota === "publicacoes"){
    switch ($metodo){
        case "GET":
            readPublicacoes($con);
            break;

        case "POST":
            createPublicacoes($con, $corpo);
            break;

        default:
            teste();
    }
}
elseif ($rota === "curtidas"){
    switch ($metodo){
        case "GET":
            readCurtidas($con);
            break;
        case "POST":
            createCurtidas($con, $corpo);
            break;
        default:
            teste();
    }
}
