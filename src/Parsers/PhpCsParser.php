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
use Symfony\Component\Process\Process;

class PhpCsParser extends BaseParser implements ParserInterface{

    public function run($iterator, MessageList &$messageList){
        foreach($iterator as $file){
            $builder = $this->getBuilder('phpcs');
            $builder->setArguments(array('--report=xml', $file));

            echo $builder->getProcess()->getCommandLine() . PHP_EOL;
            $process = new Process($builder->getProcess()->getCommandLine());
            $process->run();

            $output = $process->getOutput();
            $messageList->addMessages((string) $file, $this->parseOutput($output));
        }
    }

    protected function parseOutput($output)
    {
        $output = simplexml_load_string($output);

        /**
         * @var \SimpleXmlElement $file
         */
        $result = array();
        $file = $output->file;
        $config = new Config();
        if($file !== null && $file->count() > 0) {
            foreach ($file->children() as $phpcsMessage) {
                $messageObject = new Message('phpcs');
                $lineNr = (int)$phpcsMessage['line'];
                $messageObject->setLine($lineNr);
                $messageObject->setMessage((string)$phpcsMessage);

                $severity = (int)$phpcsMessage['severity'];
                $errorLevel = Message::LEVEL_INFO;
                if ($severity >= $config->getPhpcsWarningLevel()) {
                    $errorLevel = Message::LEVEL_WARNING;
                }
                if ($severity >= $config->getPhpcsErrorLevel()) {
                    $errorLevel = Message::LEVEL_ERROR;
                }
                $messageObject->setErrorLevel($errorLevel);
                $result[$lineNr][] = $messageObject;
            }
        }
        return $result;
    }

} 