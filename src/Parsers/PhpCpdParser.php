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
use SebastianBergmann\PHPCPD\Detector\Detector;
use SebastianBergmann\PHPCPD\Detector\Strategy\DefaultStrategy;
use Symfony\Component\Process\Process;

class PhpCpdParser extends BaseParser implements ParserInterface{

    public function run($iterator, MessageList &$messageList){
        $detector = new Detector(new DefaultStrategy(), null);
        $clones = $detector->copyPasteDetection($iterator);
        if(count($clones) == 0){
            return null;
        }
        foreach($clones as $clone){

        }
        return array();
    }

    protected function parseOutput($output)
    {
        $output = simplexml_load_string($output);

        /**
         * @var \SimpleXmlElement $file
         */
        $file = $output->file;
        $result = array();
        $config = new Config();
        foreach($file->children() as $phpmdMessage){
            $messageObject = new Message('phpmd');
            $lineNr = (int) $phpmdMessage['beginline'];
            $messageObject->setLine($lineNr);
            $messageObject->setMessage(trim((string) $phpmdMessage));

            $priority = (int) $phpmdMessage['priority'];
            $errorLevel = Message::LEVEL_INFO;
            if($priority >= $config->getPhpcsWarningLevel()){
                $errorLevel = Message::LEVEL_WARNING;
            }
            if($priority >= $config->getPhpcsErrorLevel()){
                $errorLevel = Message::LEVEL_ERROR;
            }
            $messageObject->setErrorLevel($errorLevel);
            $result[$lineNr][] = $messageObject;
        }
        return $result;
    }

} 