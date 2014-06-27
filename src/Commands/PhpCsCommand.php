<?php
/**
 * Created by PhpStorm.
 * User: clemens
 * Date: 15-06-14
 * Time: 17:52
 */

namespace PhpGrade\Commands;

use PhpGrade\Config;
use PhpGrade\Formatters\ConsoleFormatter;
use PhpGrade\Message;
use PhpGrade\Parsers\ParserInterface;
use PhpGrade\Parsers\PhpCsParser;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class PhpCsCommand extends BaseCommand
{

    protected function configure()
    {
        $this
          ->setName('cs')
          ->setDescription('Run PHPCS. This is implicit called by run.')
          ->addArgument(
            'file',
            InputArgument::REQUIRED,
            'file to run phpcs against.'
          );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        if (!file_exists($file)) {
            throw new \Exception("Location $file does not exists.");
        };

        $parser = new PhpCsParser();
        $messages = $parser->run($file);

        $formatter = new ConsoleFormatter();
        $formatter->format($messages);
    }

}