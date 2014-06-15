<?php
/**
 * Created by PhpStorm.
 * User: clemens
 * Date: 15-06-14
 * Time: 17:52
 */

namespace PhpGrade\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class PhpCsCommand extends BaseCommand {

  protected function configure() {
    $this
      ->setName('cs')
      ->setDescription('Run PHPCS. This is implicit called by run.')
      ->addArgument(
        'file',
        InputArgument::REQUIRED,
        'file to run phpcs against.'
      );
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $file = $input->getArgument('file');
    if (!file_exists($file)) {
      throw new \Exception("Location $file does not exists.");
    }
    ;
    // phpcs --report=xml $1
    $builder = $this->getBuilder('phpcs');
    $builder->setArguments(array('--report=xml', $file));

    echo $builder->getProcess()->getCommandLine() . PHP_EOL;
    $process = new Process($builder->getProcess()->getCommandLine());
    $process->run();

    // executes after the command finishes
    //if (!$process->isSuccessful()) {
    //  throw new \RuntimeException($process->getErrorOutput());
    //}

    print $process->getOutput();

  }
}