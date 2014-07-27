<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 27-6-14
 * Time: 16:15
 */

namespace PhpGrade\Formatters;


use PhpGrade\Message;

class AngularFormatter extends BaseFormatter
{
    /* @var boolean $runServer */
    private $runServer = false;

    public function format($messages)
    {
        $phpGradeRoot = __DIR__ . '/../../';
        $resourceRoot = $phpGradeRoot . 'resource/web/';
        $tempDir = $phpGradeRoot . 'tmp/';
        $this->ensureDirectory($tempDir);
        $this->recurseCopy($resourceRoot, $tempDir);
        $this->ensureDirectory($tempDir . 'data/');

        $index = array('files' => array());
        $totalCounter = array(
            'info' => 0,
            'warning' => 0,
            'error' => 0
        );
        foreach ($messages as $filename => $file) {
            $counter = array(
                'info' => 0,
                'warning' => 0,
                'error' => 0
            );
            foreach ($file as &$line) {
                foreach ($line as &$message) {
                    $message = $this->messageToArray($message);
                    switch ($message['level']) {
                        case Message::LEVEL_INFO:
                            $counter['info']++;
                            break;
                        case Message::LEVEL_WARNING:
                            $counter['warning']++;
                            break;
                        case Message::LEVEL_ERROR:
                            $counter['error']++;
                            break;
                    }
                }
            }

            $totalCounter['info'] += $counter['info'];
            $totalCounter['warning'] += $counter['warning'];
            $totalCounter['error'] += $counter['error'];

            $file = $this->mergeSourceCode($file, $filename);
            $json = json_encode($file);
            $jsonFileName = $tempDir . 'data/' . sha1($filename) . '.json';
            $index['files'][] = array(
                'resource' => sha1($filename) . '.json',
                'filename' => $filename,
                'messages' => $counter
            );
            if (!is_dir(dirname($jsonFileName))) {
                mkdir(dirname($jsonFileName), 0777, true);
            }
            file_put_contents($jsonFileName, $json);
        }

        $index['counters'] = $totalCounter;
        file_put_contents($tempDir . 'data/index.json', json_encode($index));

        $outputDir = $this->getOutputDir();
        if (substr($outputDir, -1) == '/') {
            $outputDir = substr($outputDir, 0, -1);
        }

        if ($outputDir !== null) {
            if (file_exists($outputDir . '/data/history.json')) {
                $this->getOutput()->writeln('Loading history.');
                $history = json_decode(file_get_contents($outputDir . '/data/history.json'), true);
            } else {
                $this->getOutput()->writeln('Empty history initialised: ' . $outputDir . '/data/history.json');
                $history = array();
            }
            $history[] = $index;
            file_put_contents($tempDir . 'data/history.json', json_encode($history));
            $this->recurseCopy($tempDir, $outputDir);
        }

        if ($outputDir === null) {
            $outputDir = $tempDir;
        }

        if ($this->isRunServer()) {
            rename($outputDir . '/index.htm', $outputDir . '/index.php');
            $server = "localhost:8123";
            $this->getOutput()->writeln("Starting built-in php server on http://$server");
            exec('php -S ' . $server . ' -t "' . $outputDir . '"');
        }
    }

    private function messageToArray(Message $message)
    {
        return array(
            'tool' => $message->getTool(),
            'level' => $message->getErrorLevel(),
            'message' => $message->getMessage()
        );
    }

    private function mergeSourceCode(array $messages, $filename)
    {
        $source = file($filename);
        $result = array();
        foreach ($source as $linenr => $line) {
            $lineObj = array(
                'nr' => $linenr + 1,
                'line' => str_replace("\n", "", $line),
                'msg' => array(),
            );
            if (isset($messages[$linenr + 1])) {
                $lineObj['msg'] = $messages[$linenr + 1];
            }
            $result[] = $lineObj;
        }
        return array('filename' => $filename, 'lines' => $result);
    }

    private function recurseCopy($source, $dest)
    {
        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }

        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }

        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            $this->recurseCopy("$source/$entry", "$dest/$entry");
        }

        // Clean up
        $dir->close();
        return true;
    }

    private function ensureDirectory($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }

    /**
     * @return boolean
     */
    public function isRunServer()
    {
        return $this->runServer;
    }

    /**
     * @param boolean $runServer
     */
    public function setRunServer($runServer)
    {
        $this->runServer = $runServer;
    }
}
