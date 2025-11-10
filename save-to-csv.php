<?php
// ДОДАНО: Підключаємо наш відправник
include_once 'telegram_sender.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Невірний метод запиту']);
    exit;
}

$name = $_POST['name'] ?? 'Не вказано';
$phone = $_POST['phone'] ?? 'Не вказано';
$email = $_POST['email'] ?? 'Не вказано';
$tariff = $_POST['tariff'] ?? 'Не вказано';
$payment_type = $_POST['payment_type'] ?? 'Не вказано';
$date = date('Y-m-d H:i:s');

if (empty($name) || empty($phone) || empty($email)) {
    echo json_encode(['status' => 'error', 'message' => 'Будь ласка, заповніть всі поля']);
    exit;
}

// ... (Ваш код для запису в CSV ... fputcsv і т.д.) ...
// Тут відбувається запис у CSV, залишаємо його як є
$filename = 'data.csv';
$file_exists = file_exists($filename);
$file = fopen($filename, 'a');
if ($file === false) {
    echo json_encode(['status' => 'error', 'message' => 'Неможливо відкрити файл для запису']);
    exit;
}
if (!$file_exists || filesize($filename) == 0) {
    $headers = ['Дата', 'Ім\'я', 'Телефон', 'Email', 'Тариф', 'Тип оплати'];
    fputcsv($file, $headers);
}
$data_row = [$date, $name, $phone, $email, $tariff, $payment_type];
fputcsv($file, $data_row);
fclose($file);

// --- ДОДАНО: Формування та відправка повідомлення в Telegram ---
try {
    // Формуємо красиве повідомлення
    $message = "<b>💸 Нова заявка на ОПЛАТУ!</b>\n\n";
    $message .= "<b>Тариф:</b> {$tariff}\n";
    $message .= "<b>Тип оплати:</b> {$payment_type}\n\n";
    $message .= "<b>Ім'я:</b> {$name}\n";
    $message .= "<b>Телефон:</b> {$phone}\n";
    $message .= "<b>Email:</b> {$email}";

    // Відправляємо
    sendTelegramNotification($message);
} catch (Exception $e) {
    // Якщо телеграм не спрацює, це не має зламати сайт
    // Можна записати помилку в лог
}
// -------------------------------------------------------------

echo json_encode(['status' => 'success', 'message' => 'Дані успішно збережено']);
exit;
?>