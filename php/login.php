<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Проверяем существование пользователя
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Проверяем пароль
        if (password_verify($password, $user['password'])) {
            // Устанавливаем куки для сохранения сессии
            setcookie("username", $username, time() + (86400 * 30), "/"); // Кука на 30 дней
            echo "Вход успешен! Привет, $username!";
        } else {
            echo "Неверный пароль.";
        }
    } else {
        echo "Пользователь не найден.";
    }
}
?>
