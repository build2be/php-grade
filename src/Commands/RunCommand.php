<?php

namespace PhpGrade\Commands;

use PhpGrade\Formatters\ConsoleFormatter;
use PhpGrade\MessageList;
use PhpGrade\Parsers\ParserInterface;
use PhpGrade\Parsers\PhpCpdParser;
use PhpGrade\Parsers\PhpCsParser;
use PhpGrade\Parsers\PhpDcdParser;
use PhpGrade\Parsers\PhpMdParser;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Finder\Finder;


class RunCommand extends BaseCommand
{

    protected function configure()
    {
        $this
          ->setName('run')
          ->setDescription('Run all available grading types.')
          ->addOption('tests','t',
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'Test programs to run (all by default) options: phpcs, phpmd, phpdcd, phpcpd'
          )
          ->addArgument(
            'location',
            InputArgument::REQUIRED,
            'Location to grade. This can be a file or directory'
          );
    }

    /**
     * Run all available grading (sub) commands
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $location = $input->getArgument('location');
        if (!file_exists($location)) {
            throw new \Exception("Location $location does not exists.");
        }

        if (is_dir($location)) {

        }


        $finder = new Finder();
        $finder->files()->in($location);

        $messages = new MessageList();

        $tools = $input->getOption('tests');
        if(empty($tools) || $tools === null){
            $tools = array('all');
        }

        $parsers = array();
        if(in_array('phpcs', $tools) || in_array('all', $tools)){
            $parsers[] = new PhpCsParser();
        }
        if(in_array('phpmd', $tools) || in_array('all', $tools)){
            $parsers[] = new PhpMdParser();
        }
        if(in_array('phpcpd', $tools) || in_array('all', $tools)){
            $parsers[] = new PhpCpdParser();
        }
        if(in_array('phpdcd', $tools) || in_array('all', $tools)){
            $parsers[] = new PhpDcdParser();
        }

        foreach($parsers as $parser){
            /**
             * @var ParserInterface $parser
             */
            $parser->run($finder, $messages);
        }

        $formatter = new ConsoleFormatter();
        $formatter->format($messages->getMessages());
    }
}
