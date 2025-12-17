<?php
require_once 'config/config.php';
require_once 'utils/TelegramBot.php';

$bot = new TelegramBot();
$result = $bot->testConnection();
echo 'Bot test result: ' . ($result['ok'] ? 'SUCCESS' : 'FAILED') . PHP_EOL;
if (!$result['ok']) {
    echo 'Error: ' . ($result['description'] ?? 'Unknown') . PHP_EOL;
}
?>
