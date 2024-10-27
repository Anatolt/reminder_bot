<?php

$botToken = "your_api_key"; 
$filePath = "users.json"; // Файл для хранения user_id пользователей - его надо создать и вписать туда свой айдишник, который можно посмотреть у бота https://t.me/useridinfobot

// Функция для добавления пользователя в файл
function addUser($userId) {
    global $filePath;
    $users = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];
    if (!in_array($userId, $users)) {
        $users[] = $userId;
        file_put_contents($filePath, json_encode($users));
    }
}

// Функция для отправки сообщения пользователю
function sendMessage($chatId, $message) {
    global $botToken;
    $url = "https://api.telegram.org/bot$botToken/sendMessage";
    $data = [
        'chat_id' => $chatId,
        'text' => $message
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    $context = stream_context_create($options);
    file_get_contents($url, false, $context);
}

// Чтение user_id из файла и отправка сообщения "Как дела?"
$users = json_decode(file_get_contents($filePath), true);
foreach ($users as $user) {
    sendMessage($user, "Как дела?");
}

?>
