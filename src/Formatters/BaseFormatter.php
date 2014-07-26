<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 27-6-14
 * Time: 10:36
 */

namespace PhpGrade\Formatters;


use PhpGrade\Message;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BaseFormatter
 * @package PhpGrade\Formatters
 */
class BaseFormatter
{
    /* @var string $outputDir */
    var $outputDir;

    /** @var OutputInterface $output */
    var $output = null;

    public function format($messages)
    {
        foreach ($messages as $filename => $file) {
            $this->formatFile($filename, $file);
        }
    }

    protected function formatFile($filename, $file)
    {
        foreach ($file as $line) {
            $this->formatLine($line);
        }
    }

    protected function formatLine($line)
    {
        foreach ($line as $message) {
            $this->formatMessage($message);
        }
    }

    protected function formatMessage(Message $message)
    {
        return print_r($message, true);
    }

    /**
     * @return string
     */
    public function getOutputDir() {
        return $this->outputDir;
    }

    /**
     * @param string $outputDir
     */
    public function setOutputDir($outputDir) {
        $this->outputDir = $outputDir;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput() {
       return $this->output;
    }

    /**
     * @param OutputInterface $output
     */
    public function setOutput($output) {
        $this->output = $output;
    }
}