<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Хэшируем пароль для безопасности
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Проверяем, существует ли пользователь
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);

    if ($stmt->rowCount() > 0) {
        echo "Пользователь с таким именем уже существует!";
    } else {
        // Добавляем нового пользователя
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        if ($stmt->execute([$username, $hashedPassword])) {
            echo "Регистрация успешна!";
        } else {
            echo "Ошибка регистрации.";
        }
    }
}
?>
