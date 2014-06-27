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
use Symfony\Component\Process\Process;

class PhpCsParser extends BaseParser{

    public function run($file){
        // phpcs --report=xml $1
        $builder = $this->getBuilder('phpcs');
        $builder->setArguments(array('--report=xml', $file));

        echo $builder->getProcess()->getCommandLine() . PHP_EOL;
        $process = new Process($builder->getProcess()->getCommandLine());
        $process->run();

        // executes after the command finishes
        //if (!$process->isSuccessful()) {
        //  throw new \RuntimeException($process->getErrorOutput());
        //}
        $output = $process->getOutput();
        return $this->parseOutput($output);
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
        foreach($file->children() as $phpcsMessage){
            $messageObject = new Message('phpcs');
            $lineNr = (int) $phpcsMessage['line'];
            $messageObject->setLine($lineNr);
            $messageObject->setMessage((string) $phpcsMessage);

            $severity = (int) $phpcsMessage['severity'];
            $errorLevel = Message::LEVEL_INFO;
            if($severity >= $config->getPhpcsWarningLevel()){
                $errorLevel = Message::LEVEL_WARNING;
            }
            if($severity >= $config->getPhpcsErrorLevel()){
                $errorLevel = Message::LEVEL_ERROR;
            }
            $messageObject->setErrorLevel($errorLevel);
            $result[$lineNr][] = $messageObject;
        }
        return $result;
    }

} 