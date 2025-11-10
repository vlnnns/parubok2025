<?php
// Обов'язково виклик цієї функції, перш ніж щось відправляти
function sendTelegramNotification($message) {

    // !!! ЗАМІНІТЬ ЦІ ЗНАЧЕННЯ НА ВАШІ !!!
    $token   = '7840946389:AAG1QZK6wJtjchwVXBHfxJfyMgHCm8AHyho'; // Ваш токен бота
    $chat_id = '568273519';         // Ваш Chat ID

    // Формуємо URL для запиту
    $url = "https.api.telegram.org/bot{$token}/sendMessage";

    // Формуємо параметри запиту
    $params = [
        'chat_id' => $chat_id,
        'text' => $message,
        'parse_mode' => 'HTML', // Дозволяє використовувати <b>, <i>
    ];

    // Використовуємо cURL для відправки (більш надійний спосіб)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 секунд на відправку
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}
?>