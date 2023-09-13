<?php
	
	include 'conexion.php';
	
	$pdo = new Conexion();
	//echo($_SERVER['REQUEST_METHOD']);
	//Listar registros y consultar registro
	if($_SERVER['REQUEST_METHOD'] == 'GET'){
		if(isset($_GET['id']))
		{
			$sql = $pdo->prepare("SELECT * FROM datos WHERE id=:id");
			$sql->bindValue(':id', $_GET['id']);
			$sql->execute();
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			header("HTTP/1.1 200 hay datos");
			echo json_encode($sql->fetchAll());
			exit;
			
			} else {
			
			$sql = $pdo->prepare("SELECT * FROM datos");
			$sql->execute();
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			header("HTTP/1.1 200 hay datos");
			echo json_encode($sql->fetchAll());
			exit;
		}
	}
	
	//Insertar registro
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$sql = "INSERT INTO datos (nombre, apellido) VALUES(:nombre, :apellido)";
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':nombre', $_POST['nombre']);
		$stmt->bindValue(':apellido', $_POST['apellido']);
		$stmt->execute();
		$idPost = $pdo->lastInsertId();
		if($idPost)
		{
			header("HTTP/1.1 200 Ok");
			echo json_encode($idPost);
			exit;
		}
	}
	
// Actualizar registro
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    try {
        // Obtener los parámetros de la solicitud PUT
        parse_str(file_get_contents("php://input"), $putData);
        
        $id = $putData['id'];
        $nombre = $putData['nombre'];
        $apellido = $putData['apellido'];

        // Preparar y ejecutar la consulta de actualización
        $sql = "UPDATE datos SET nombre=:nombre, apellido=:apellido WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':apellido', $apellido);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $response = array("message" => "Registro actualizado exitosamente");
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } catch (Exception $e) {
        // Manejo de errores en caso de excepciones
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(array("error" => $e->getMessage()));
        exit;
    }
}

	//Eliminar registro
	if($_SERVER['REQUEST_METHOD'] == 'DELETE')
	{
		$id = $_GET['id'];
		
		$sql = "DELETE FROM datos WHERE id=:id";
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':id', $_GET['id']);
		$stmt->execute();
		header("HTTP/1.1 200 Ok");
		exit;
	}
	//Si no corresponde a ninguna opción anterior
	header("HTTP/1.1 400 Bad Request");
?>