<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

class TelegramService
{
    private $telegram;
    private $token = '5556253297:AAEmsdT0Nbpg_u-MyKNMBRffCqRlhSrLC5U'; 
    //private $ps;

    function __construct(/* PdfService $ps */)
    {
        $this->telegram = new Api($this->token);
        //$this->ps=$ps;
    }

    public function sendMessage($chatId, $message)
    {
        try {
            $this->telegram->sendMessage([
                'chat_id' => $chatId, 
                'text' => $message
              ]);
            
            return true;

            } catch (\Throwable $th) {
                return false;
        }
          //$messageId = $response->getMessageId();
    }

    public function sendFile($chatId, $doc)
    {
        try {
            $this->telegram->sendDocument([
                'chat_id' => $chatId, 
                'document' => new InputFile($doc)
              ]);
            
            return true;

            } catch (\Throwable $th) {
                dd($th);
                return false;
        }
          //$messageId = $response->getMessageId();
    }
}


?>