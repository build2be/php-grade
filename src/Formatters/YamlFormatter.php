<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 27-6-14
 * Time: 14:27
 */

namespace PhpGrade\Formatters;


use PhpGrade\Message;
use Symfony\Component\Yaml\Yaml;

class YamlFormatter extends BaseFormatter{
    public function format($messages)
    {
        foreach($messages as &$file){
            foreach($file as &$line){
                foreach($line as &$message){
                    $message = $this->messageToArray($message);
                }
            }
        }
        echo Yaml::dump($messages, 10, 4, false, true);
    }

    private function messageToArray(Message $message){
        return array(
            'tool' => $message->getTool(),
            'level' => $message->getErrorLevel(),
            'message' => $message->getMessage()
        );
    }

} 