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
    public function format($messages, $outputDir = null, $serve = false)
    {
        $phpGradeRoot = __DIR__ . '/../../';
        $resourceRoot = $phpGradeRoot . 'resource/web/';
        $tempDir = $phpGradeRoot . 'tmp/';
        $this->recurse_copy($resourceRoot, $tempDir);
        $index = array();

        foreach ($messages as $filename => $file) {
            $counter = array(
                'info' => 0,
                'warning' => 0,
                'error' => 0
            );
            foreach ($file as &$line) {
                foreach ($line as &$message) {
                    $message = $this->messageToArray($message);
                    switch($message['level']){
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
            $file = $this->mergeSourceCode($file, $filename);
            $json = json_encode($file);
            $jsonFileName = $tempDir . 'data/' . sha1($filename) . '.json';
            $index[] = array(
              'resource' => sha1($filename) . '.json',
              'filename' => $filename,
              'messages' => $counter
            );
            if (!is_dir(dirname($jsonFileName))) {
                mkdir(dirname($jsonFileName), 0777, true);
            }
            file_put_contents($jsonFileName, $json);
        }
        file_put_contents($tempDir . 'data/index.json', json_encode($index));
    }

    private function messageToArray(Message $message)
    {
        return array(
          'tool' => $message->getTool(),
          'level' => $message->getErrorLevel(),
          'message' => $message->getMessage()
        );
    }

    private function mergeSourceCode(array $messages, $filename){
        $source = file($filename);
        $result = array();
        foreach($source as $linenr => $line){
            $line = htmlspecialchars($line);
            $lineObj = array(
                'nr' => $linenr + 1,
                'line' => str_replace("\n", "", $line),
                'msg' => array(),
            );
            if(isset($messages[$linenr])){
                $lineObj['msg'] = $messages[$linenr];
            }
            $result[] = $lineObj;
        }
        return array('filename' => $filename, 'lines' => $result);
    }

    function recurse_copy($source, $dest)
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
            $this->recurse_copy("$source/$entry", "$dest/$entry");
        }

        // Clean up
        $dir->close();
        return true;
    }


} 