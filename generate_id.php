<?php
// Подключение к базе данных (пример подключения)
$host = 'localhost';
$db_name = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

// Функция для генерации уникального @Id
function generateUniqueId($pdo) {
    do {
        $id = '@' . substr(md5(uniqid(mt_rand(), true)), 0, 8); // Генерируем ID
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE user_id = :id");
        $stmt->execute(['id' => $id]);
        $count = $stmt->fetchColumn();
    } while ($count > 0); // Проверяем уникальность

    return $id;
}

// Основная логика
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Генерация нового уникального @Id
    $newId = generateUniqueId($pdo);

    // Сохраняем пользователя в базу (можно добавить больше полей)
    $stmt = $pdo->prepare("INSERT INTO users (user_id) VALUES (:id)");
    $stmt->execute(['id' => $newId]);

    // Отправляем новый ID клиенту
    echo json_encode(['success' => true, 'id' => $newId]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
