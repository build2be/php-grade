<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 27-6-14
 * Time: 11:19
 */

namespace PhpGrade\Parsers;


interface ParserInterface {
    public function run($file);
} 