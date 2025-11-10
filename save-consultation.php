<?php
// Підключаємо наш відправник
include_once 'telegram_sender.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Невірний метод запиту']);
    exit;
}

$name = $_POST['name'] ?? 'Не вказано';
$phone = $_POST['phone'] ?? 'Не вказано';
$email = $_POST['email'] ?? 'Не вказано';
$date = date('Y-m-d H:i:s');

if (empty($name) || empty($phone) || empty($email)) {
    echo json_encode(['status' => 'error', 'message' => 'Будь ласка, заповніть всі поля']);
    exit;
}

// ОПЦІЙНО: Збереження в CSV (рекомендую зробити)
try {
    $filename = 'consultations.csv';
    $file_exists = file_exists($filename);
    $file = fopen($filename, 'a');

    if (!$file_exists || filesize($filename) == 0) {
        $headers = ['Дата', 'Ім\'я', 'Телефон', 'Email'];
        fputcsv($file, $headers);
    }
    $data_row = [$date, $name, $phone, $email];
    fputcsv($file, $data_row);
    fclose($file);
} catch (Exception $e) {
    // Не критично, якщо не збереглося в CSV, головне - телеграм
}


// --- Формування та відправка повідомлення в Telegram ---
try {
    $message = "<b>💬 Нова заявка на КОНСУЛЬТАЦІЮ!</b>\n\n";
    $message .= "<b>Ім'я:</b> {$name}\n";
    $message .= "<b>Телефон:</b> {$phone}\n";
    $message .= "<b>Email:</b> {$email}";

    sendTelegramNotification($message);
} catch (Exception $e) {
    //
}

echo json_encode(['status' => 'success', 'message' => 'Дані успішно надіслано']);
exit;
?>