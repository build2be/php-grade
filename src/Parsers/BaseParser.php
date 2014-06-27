<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 27-6-14
 * Time: 10:30
 */

namespace PhpGrade\Parsers;


use Symfony\Component\Process\ProcessBuilder;

class BaseParser {
    protected function getBuilder($prefix)
    {
        $builder = new ProcessBuilder();
        $builder->setPrefix($prefix);
        return $builder;
    }
} 