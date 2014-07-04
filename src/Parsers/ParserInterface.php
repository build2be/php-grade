<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 27-6-14
 * Time: 11:19
 */

namespace PhpGrade\Parsers;


use PhpGrade\MessageList;
use Symfony\Component\Finder\Finder;

interface ParserInterface
{
    public function run(Finder $iterator, MessageList &$messageList);
} 