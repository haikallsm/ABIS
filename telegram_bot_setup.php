<?php
/**
 * Telegram Bot Setup Helper
 * ABIS - Aplikasi Desa Digital
 *
 * This script helps you test and configure Telegram bot integration
 */

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>🤖 Telegram Bot Setup - ABIS Project</h1>";

// Configuration - Update these with your actual values
$BOT_TOKEN = 'YOUR_BOT_TOKEN_HERE'; // From @BotFather
$CHAT_ID = 'YOUR_CHAT_ID_HERE'; // From getUpdates or user chat

echo "<h2>📋 Configuration</h2>";
echo "<div style='background:#f5f5f5; padding:15px; margin:10px 0; border-radius:5px;'>";
echo "<p><strong>BOT_TOKEN:</strong> " . (empty($BOT_TOKEN) || $BOT_TOKEN === 'YOUR_BOT_TOKEN_HERE' ? "<span style='color:red'>❌ Not configured</span>" : "<span style='color:green'>✅ Configured</span>") . "</p>";
echo "<p><strong>CHAT_ID:</strong> " . (empty($CHAT_ID) || $CHAT_ID === 'YOUR_CHAT_ID_HERE' ? "<span style='color:red'>❌ Not configured</span>" : "<span style='color:green'>✅ Configured</span>") . "</p>";
echo "</div>";

echo "<h2>🧪 Bot Testing</h2>";

if ($BOT_TOKEN !== 'YOUR_BOT_TOKEN_HERE' && !empty($BOT_TOKEN)) {
    // Test getMe
    $url = "https://api.telegram.org/bot$BOT_TOKEN/getMe";
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if ($data && $data['ok']) {
        echo "<p style='color:green'>✅ Bot Connection: SUCCESS</p>";
        echo "<p><strong>Bot Name:</strong> @" . $data['result']['username'] . "</p>";
        echo "<p><strong>Bot ID:</strong> " . $data['result']['id'] . "</p>";
    } else {
        echo "<p style='color:red'>❌ Bot Connection: FAILED</p>";
        echo "<p><strong>Error:</strong> " . ($data ? $data['description'] : 'Unknown error') . "</p>";
    }

    // Test sendMessage if CHAT_ID is configured
    if ($CHAT_ID !== 'YOUR_CHAT_ID_HERE' && !empty($CHAT_ID)) {
        echo "<h3>📤 Test Message Sending</h3>";

        $test_message = "🧪 Test message from ABIS Bot\n⏰ Time: " . date('Y-m-d H:i:s') . "\n✅ Bot is working!";

        $send_url = "https://api.telegram.org/bot$BOT_TOKEN/sendMessage";
        $postData = [
            'chat_id' => $CHAT_ID,
            'text' => $test_message,
            'parse_mode' => 'HTML'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $send_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);

        if ($result && $result['ok']) {
            echo "<p style='color:green'>✅ Message Sent: SUCCESS</p>";
            echo "<p><strong>Message ID:</strong> " . $result['result']['message_id'] . "</p>";
            echo "<p><em>Check your Telegram for the test message!</em></p>";
        } else {
            echo "<p style='color:red'>❌ Message Sent: FAILED</p>";
            echo "<p><strong>HTTP Code:</strong> $httpCode</p>";
            echo "<p><strong>Error:</strong> " . ($result ? $result['description'] : 'Unknown error') . "</p>";
        }
    } else {
        echo "<p style='color:orange'>⚠️ CHAT_ID not configured - cannot test message sending</p>";
    }
} else {
    echo "<p style='color:orange'>⚠️ BOT_TOKEN not configured - cannot test bot connection</p>";
}

echo "<h2>📚 Setup Instructions</h2>";
echo "<div style='background:#e3f2fd; padding:15px; margin:10px 0; border-radius:5px; border-left:4px solid #2196f3;'>";
echo "<h3>1. Create Bot with @BotFather</h3>";
echo "<ol>";
echo "<li>Open Telegram and search for <strong>@BotFather</strong></li>";
echo "<li>Send command: <code>/newbot</code></li>";
echo "<li>Follow instructions to create your bot</li>";
echo "<li>Copy the <strong>BOT_TOKEN</strong> provided by @BotFather</li>";
echo "</ol>";

echo "<h3>2. Get Your CHAT_ID</h3>";
echo "<p><strong>Method 1 - Via getUpdates:</strong></p>";
echo "<ol>";
echo "<li>Send a message to your bot from your Telegram account</li>";
echo "<li>Visit: <code>https://api.telegram.org/bot[BOT_TOKEN]/getUpdates</code></li>";
echo "<li>Find your chat ID in the JSON response</li>";
echo "</ol>";

echo "<p><strong>Method 2 - Via @userinfobot:</strong></p>";
echo "<ol>";
echo "<li>Message <strong>@userinfobot</strong> in Telegram</li>";
echo "<li>Copy your Telegram User ID (this is your CHAT_ID)</li>";
echo "</ol>";

echo "<h3>3. Configure in ABIS</h3>";
echo "<p>Update these values in your database or config:</p>";
echo "<ul>";
echo "<li><strong>BOT_TOKEN:</strong> From @BotFather</li>";
echo "<li><strong>CHAT_ID:</strong> Your Telegram user ID</li>";
echo "</ul>";
echo "</div>";

echo "<h2>🔧 Integration Code Example</h2>";
echo "<div style='background:#f5f5f5; padding:15px; margin:10px 0; border-radius:5px; font-family:monospace;'>";
echo "<pre>";
echo htmlspecialchars('<?php
// Telegram Bot Integration Example
class TelegramBot {
    private $botToken;
    private $chatId;

    public function __construct($botToken, $chatId) {
        $this->botToken = $botToken;
        $this->chatId = $chatId;
    }

    public function sendMessage($message) {
        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";

        $postData = [
            \'chat_id\' => $this->chatId,
            \'text\' => $message,
            \'parse_mode\' => \'HTML\'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    // Example: Send letter approval notification
    public function sendLetterApproval($userName, $letterType) {
        $message = "✅ <b>Surat Disetujui</b>\n\n";
        $message .= "👤 <b>User:</b> $userName\n";
        $message .= "📄 <b>Jenis Surat:</b> $letterType\n";
        $message .= "⏰ <b>Waktu:</b> " . date(\'d/m/Y H:i\') . "\n\n";
        $message .= "📋 Status: <b>APPROVED</b>";

        return $this->sendMessage($message);
    }
}

// Usage example:
/*
$bot = new TelegramBot(\'YOUR_BOT_TOKEN\', \'YOUR_CHAT_ID\');
$result = $bot->sendLetterApproval(\'John Doe\', \'Surat Keterangan Domisili\');

if ($result && $result[\'ok\']) {
    echo "Message sent successfully!";
} else {
    echo "Failed to send message: " . $result[\'description\'];
}
*/
?>');
echo "</pre>";
echo "</div>";

echo "<h2>📊 Database Integration</h2>";
echo "<p>To store bot configuration in database:</p>";
echo "<div style='background:#f5f5f5; padding:15px; margin:10px 0; border-radius:5px; font-family:monospace;'>";
echo "<pre>";
echo htmlspecialchars('CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(255) UNIQUE,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO settings (setting_key, setting_value) VALUES
(\'telegram_bot_token\', \'YOUR_BOT_TOKEN_HERE\'),
(\'telegram_chat_id\', \'YOUR_CHAT_ID_HERE\');
');
echo "</pre>";
echo "</div>";

echo "<hr>";
echo "<p><em>Setup guide generated at: " . date('Y-m-d H:i:s') . "</em></p>";
?>

