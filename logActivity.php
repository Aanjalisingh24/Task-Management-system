<?php
function logActivity($user_id, $task_id, $action) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO activity_logs (user_id, task_id, action) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $task_id, $action);
    $stmt->execute();
}

function getActivityLogs($user_id, $role) {
    global $conn;
    if ($role === 'admin') {
        $stmt = $conn->prepare("SELECT * FROM activity_logs");
    } else {
        $stmt = $conn->prepare("SELECT * FROM activity_logs WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

?>
