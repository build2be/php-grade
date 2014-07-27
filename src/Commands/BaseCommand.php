<?php

/**
 * Contains \PhpGrade\Commands\BaseCommand.php
 *
 * PHP version 5
 *
 * @category  Command
 * @package   PhpGrade
 * @author    Clemens Tolboom <clemens@build2be.com>
 * @author    Martijn Braam <martijn@brixit.nl>
 * @copyright 2014 http://build2be.com
 * @license   https://github.com/build2be/php-grade/blob/master/LICENSE ASIS
 * @link      https://github.com/build2be/php-grade
 */

namespace PhpGrade\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Put all utility methods and properties in here.
 *
 * @category Command
 * @package  PhpGrade
 * @author   Clemens Tolboom <clemens@build2be.com>
 * @license  https://github.com/build2be/php-grade/blob/master/LICENSE ASIS
 * @link     https://github.com/build2be/php-grade
 */
class BaseCommand extends Command
{

    /**
     * Constructor
     *
     * @param null $name Command name to execute
     */
    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    /**
     * Log given text based on log level given and current verbosity level.
     *
     * @param OutputInterface $output output interface to write to.
     * @param string          $text   text to write.
     * @param int             $level  log level to suppress.
     *
     * @return null
     */
    public function log(OutputInterface $output, $text, $level = 1)
    {
        if ($output->getVerbosity() > $level) {
            $output->writeln("$level: $text");
        }
    }
}
