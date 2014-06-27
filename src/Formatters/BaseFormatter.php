<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 27-6-14
 * Time: 10:36
 */

namespace PhpGrade\Formatters;


class BaseFormatter {
    public function format($messages){
        foreach($messages as $filename => $file){
            $this->formatFile($filename, $file);
        }
    }

    protected function formatFile($filename, $file){
        foreach($file as $line){
            $this->formatLine($line);
        }
    }

    protected function formatLine($line){
        foreach($line as $message){
            $this->formatMessage($message);
        }
    }

    protected function formatMessage($message){
        return print_r($message, true);
    }
} 