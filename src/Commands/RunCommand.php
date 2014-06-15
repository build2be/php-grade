<?php

namespace PhpGrade\Commands;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Finder\Finder;


class RunCommand extends BaseCommand {

  protected function configure() {
    $this
      ->setName('run')
      ->setDescription('Run all available grading types.')
      ->addArgument(
        'location',
        InputArgument::REQUIRED,
        'Location to grade. This can be a file or directory'
      );
  }

  /**
   * Run all available grading (sub) commands
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $location = $input->getArgument('location');
    if (!file_exists($location)) {
      throw new \Exception("Location $location does not exists.");
    }

    if (is_dir($location)) {

    }


    $finder = new Finder();
    $finder->files()->in(__DIR__);

    foreach ($finder as $file) {

    }


    $command = $this->getApplication()->find('cs');

    $arguments = array(
      'command' => 'cs',
      'file' => $location,
    );

    $input = new ArrayInput($arguments);
    $returnCode = $command->run($input, $output);
  }
}
