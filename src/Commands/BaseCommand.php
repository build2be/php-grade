<?php

namespace PhpGrade\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class BaseCommand extends Command
{

    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    /**
     * log based on log level given and current verbosity level.
     *
     * @param OutputInterface $output
     * @param $text
     * @param int $level
     */
    public function log(OutputInterface $output, $text, $level = 1) {
          if ($output->getVerbosity() > $level) {
            $output->writeln("$level: $text");
          }
      }
}
