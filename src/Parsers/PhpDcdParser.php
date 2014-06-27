<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 27-6-14
 * Time: 10:29
 */

namespace PhpGrade\Parsers;


use PhpGrade\Config;
use PhpGrade\Message;
use PhpGrade\MessageList;
use SebastianBergmann\PHPDCD\Detector;
use Symfony\Component\Finder\Finder;

class PhpDcdParser extends BaseParser implements ParserInterface{

    public function run(Finder $iterator, MessageList &$messageList){
        $detector = new Detector();
        $files = iterator_to_array($iterator->name("*.php"));
        $deadCode = $detector->detectDeadCode($files, true);

        if(count($deadCode) == 0){
            return null;
        }

        foreach($deadCode as $deadCodeBlock){
            $filename = (string) $deadCodeBlock['file'];
            $lineNo = $deadCodeBlock['line'];
            $messageObject = new Message('phpdcd');
            $messageObject->setLine($lineNo);
            $messageObject->setErrorLevel(Message::LEVEL_INFO);
            $messageObject->setMessage($deadCodeBlock['loc'] . ' lines of dead code');
            $result[$lineNo][] = $messageObject;
            $messageList->addMessages($filename, $result);
        }
    }

} 