<?php

namespace App\Services;

use GuzzleHttp\Client;
use SevenEcks\Tableify\Tableify;

class TelegramService
{
    protected $telegramBotToken;
    protected $apiUrl;

    public function __construct()
    {
        $this->telegramBotToken = env('TELEGRAM_BOT_TOKEN'); // Ambil token dari file .env
        $this->apiUrl = "https://api.telegram.org/bot{$this->telegramBotToken}/";
    }
    public function sendMessage($chatId, $message)
    {
        $client = new Client();

        $response = $client->post("{$this->apiUrl}sendMessage", [
            'json' => [
                'chat_id' => $chatId,
                'text' => $message,
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
    public function sendMessageTable($chatId, $message) {
        $url = "https://api.telegram.org/bot{$this->telegramBotToken}/sendMessage?chat_id={$chatId}&parse_mode=html&text=" . urlencode($message);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $html = curl_exec($ch);
        var_dump($html);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // echo "Output:".$html;  // you can print the output for troubleshooting
        curl_close($ch);
    }
    public function getMessage($chatId){
        $url = "https://api.telegram.org/bot{$this->telegramBotToken}/getMessage";
        $response = Http::get($url, [
            'chat_id' => $chatId,
        ]);

        return $response->json(); // Return the API response
    }
    public function deleteMessage($chatId, $messageId)
    {
        $client = new Client();

        $response = $client->post("{$this->apiUrl}deleteMessage", [
            'json' => [
                'chat_id'    => $chatId,
                'message_id' => $messageId,
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
