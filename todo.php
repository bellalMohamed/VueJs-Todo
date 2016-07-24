<?php

header('Content-Type: application/json');

$db = new PDO("mysql:host=localhost;dbname=vuejstodo", "root", "");

$method = strtolower($_SERVER['REQUEST_METHOD']);

if ($method === 'get') {
	$todos = $db->query("SELECT id, name FROM todos");
	if ($todos->rowCount() === 0) {
		echo json_encode([]);
		die();
	}
	echo json_encode($todos->fetchAll(PDO::FETCH_OBJ));
	die();
}


if ($method === 'post') {
	if (!isset($_POST['name']) || empty(trim($_POST['name']))) {
		http_response_code(400);
		die();
	}
	$todo = $db->prepare("INSERT INTO todos (name) VALUES (:name)");
	$todo->execute([
		'name' => $_POST['name']
	]);
	http_response_code(200);
	die();
}

if ($method === 'delete') {
	parse_str(file_get_contents('php://input'), $payload);
	if (!isset($payload['id']) || empty($payload['id'])) {
		http_response_code(400);
		die();
	}
	$todo = $db->prepare("DELETE FROM todos WHERE id = :id");
	$todo->execute([
		'id' => $payload['id']
	]);
	http_response_code(200);
	die();
}