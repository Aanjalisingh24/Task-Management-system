<?php
require 'auth.php';
require 'connection.php';
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$path = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$resource = $path[1] ?? null;
$id = $path[2] ?? null;


$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? '';
$token = str_replace('Bearer ', '', $authHeader);
$user = validateJWT($token);


if ($resource === 'register' && $method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $role = $data['role'] ?? 'user';

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    if ($stmt->execute()) {
        echo json_encode(["message" => "User registered"]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Registration failed"]);
    }

} elseif ($resource === 'login' && $method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'];
    $password = $data['password'];

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password, $role);
    $stmt->fetch();

    if ($stmt->num_rows === 1 && password_verify($password, $hashed_password)) {
        echo json_encode(["token" => generateJWT($id, $role)]);
    } else {
        http_response_code(401);
        echo json_encode(["message" => "Invalid credentials"]);
    }

} elseif ($resource === 'tasks') {
    if (!$user) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized']);
        exit;
    }

    if ($method === 'GET') {
        echo json_encode(getTasks($user['id'], $user['role']));

    } elseif ($method === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        $task_id = createTask($user['id'], $data['title'], $data['description']);
        echo json_encode(["message" => "Task created", "task_id" => $task_id]);

    } elseif ($method === 'PUT' && $id) {
        $data = json_decode(file_get_contents("php://input"), true);
        updateTask($user['id'], $user['role'], $id, $data['title'], $data['description'], $data['status']);
        echo json_encode(["message" => "Task updated"]);

    } elseif ($method === 'DELETE' && $id) {
        deleteTask($user['id'], $user['role'], $id);
        echo json_encode(["message" => "Task deleted"]);

    } else {
        http_response_code(400);
        echo json_encode(["message" => "Invalid task operation"]);
    }

} elseif ($resource === 'activity-logs' && $method === 'GET') {
    if (!$user) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized']);
        exit;
    }
    echo json_encode(getActivityLogs($user['id'], $user['role']));

} else {
    http_response_code(404);
    echo json_encode(['message' => 'Endpoint not found']);
}


function createTask($user_id, $title, $description) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $title, $description);
    $stmt->execute();
    $task_id = $stmt->insert_id;
    logActivity($user_id, $task_id, 'create');
    return $task_id;
}

function getTasks($user_id, $role) {
    global $conn;
    if ($role === 'admin') {
        $stmt = $conn->prepare("SELECT * FROM tasks WHERE is_deleted = 0");
    } else {
        $stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? AND is_deleted = 0");
        $stmt->bind_param("i", $user_id);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function updateTask($user_id, $role, $task_id, $title, $description, $status) {
    global $conn;
    if ($role === 'admin') {
        $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $description, $status, $task_id);
    } else {
        $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sssii", $title, $description, $status, $task_id, $user_id);
    }
    $stmt->execute();
    logActivity($user_id, $task_id, 'update');
}

function deleteTask($user_id, $role, $task_id) {
    global $conn;
    if ($role === 'admin') {
        $stmt = $conn->prepare("UPDATE tasks SET is_deleted = 1 WHERE id = ?");
        $stmt->bind_param("i", $task_id);
    } else {
        $stmt = $conn->prepare("UPDATE tasks SET is_deleted = 1 WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $task_id, $user_id);
    }
    $stmt->execute();
    logActivity($user_id, $task_id, 'delete');
}

