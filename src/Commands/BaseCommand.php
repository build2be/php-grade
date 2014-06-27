<?php

namespace PhpGrade\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\ProcessBuilder;

class BaseCommand extends Command
{

    public function __construct($name = null)
    {
        parent::__construct($name);
    }

}
