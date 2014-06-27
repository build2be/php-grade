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

class PhpMdParser extends BaseParser implements ParserInterface{

    public function run($file){
        $builder = $this->getBuilder('phpmd');
        $builder->setArguments(array(
            $file,
            'xml',
            'cleancode,codesize,controversial,design,naming,unusedcode',
          )
        );

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