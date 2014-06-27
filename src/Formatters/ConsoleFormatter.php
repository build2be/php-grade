<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 27-6-14
 * Time: 10:35
 */

namespace PhpGrade\Formatters;


use PhpGrade\Message;

class ConsoleFormatter extends BaseFormatter
{
    protected function formatFile($filename, $file)
    {
        echo $filename . PHP_EOL;
        parent::formatFile($filename, $file);
    }

    protected function formatMessage(Message $message)
    {
        printf("%5s [ %-10s ][ %-7s ] %s\n",
          (string)$message->getLine(),
          $message->getTool(),
          $message->getErrorLevel(true),
          $message->getMessage());
    }
} 