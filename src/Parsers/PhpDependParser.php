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

class PhpDependParser extends BaseParser implements ParserInterface{

    public function run($file){
        // pdepend --summary-xml=$2 $1
        $builder = $this->getBuilder('pdepend');
        $randomFile = microtime();
        $builder->setArguments(array('--summary-xml="/tmp/' . $randomFile . '"', $file));

        echo $builder->getProcess()->getCommandLine() . PHP_EOL;
        $process = new Process($builder->getProcess()->getCommandLine());
        $process->run();

        // executes after the command finishes
        //if (!$process->isSuccessful()) {
        //  throw new \RuntimeException($process->getErrorOutput());
        //}
        $process->getOutput();
        $output = file_get_contents('/tmp/' . $randomFile);
        return $this->parseOutput($output);
    }

    protected function parseOutput($output)
    {
        $output = simplexml_load_string($output);

        /**
         * @var \SimpleXmlElement $packages
         * @var \SimpleXmlElement $namespace
         * @var \SimpleXmlElement $node
         * @var \SimpleXmlElement $method
         */
        $packages = $output->package;
        foreach($packages as $namespace){
            foreach($namespace->children() as $node){
                $type = (string) $node->getName();
                if($type == 'function'){
                    $nodePath = $namespace['name'] . '/' . $node['name'];
                    $this->parseFunction($nodePath, $node);
                }

                if($type == 'class'){
                    $nodePath = $namespace['name'] . '/' . $node['name'];
                    foreach($node->children() as $method){
                        if($method->getName() == 'method'){
                            $nodePath .= '/' . $method['name'];
                            $this->parseFunction($nodePath, $method);
                        }
                    }
                }
            }
        }
        $result = array();
        $config = new Config();
        foreach($package->children() as $class){



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

    private function parseFunction($nodePath)
    {
    }

} 