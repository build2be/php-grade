<?php

namespace PhpGrade\Commands;

use PhpGrade\Formatters\AngularFormatter;
use PhpGrade\Formatters\ConsoleFormatter;
use PhpGrade\Formatters\YamlFormatter;
use PhpGrade\MessageList;
use PhpGrade\Parsers\ParserInterface;
use PhpGrade\Parsers\PhpCpdParser;
use PhpGrade\Parsers\PhpCsParser;
use PhpGrade\Parsers\PhpDcdParser;
use PhpGrade\Parsers\PhpMdParser;
use PhpGrade\Parsers\PhpSecurityParser;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;


class RunCommand extends BaseCommand
{

    protected function configure()
    {
        $this
          ->setName('run')
          ->setDescription('Run all available grading types.')
          ->addOption(
            'tests',
            't',
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'Test programs to run (all by default) options: phpcs, phpmd, phpdcd, phpcpd.'
          )
          ->addOption(
            'format',
            'f',
            InputOption::VALUE_REQUIRED,
            'Set output formatter (console by default) options: console, yaml, angular.',
            'console'
          )
          ->addOption(
            'output-dir',
            'o',
            InputOption::VALUE_REQUIRED,
            'Output directory for angular formatter.'
          )
          ->addOption(
            'serve',
            's',
            InputOption::VALUE_NONE,
            'Run PHP built-in webserver on the output directory for angular.'
          )
          ->addArgument(
            'location',
            InputArgument::REQUIRED,
            'Location to grade. This can be a file or directory.'
          )
        ;
    }

    /**
     * Run all available grading (sub) commands
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $location = $input->getArgument('location');
        if (!file_exists($location)) {
            throw new \Exception("Location $location does not exists.");
        }

        $tools = $input->getOption('tests');
        if (empty($tools) || $tools === null) {
            $tools = array('all');
        }

        $parsers = array();
        if (in_array('phpcs', $tools) || in_array('all', $tools)) {
            $parsers['phpcs'] = new PhpCsParser();
        }
        if (in_array('phpmd', $tools) || in_array('all', $tools)) {
            $parsers['phpmd'] = new PhpMdParser();
        }
        if (in_array('phpcpd', $tools) || in_array('all', $tools)) {
            $parsers['phpcpd'] = new PhpCpdParser();
        }
        if (in_array('phpdcd', $tools) || in_array('all', $tools)) {
            $parsers['phpdcd'] = new PhpDcdParser();
        }
        if (in_array('security', $tools) || in_array('all', $tools)) {
            $parsers['security'] = new PhpSecurityParser();
        }


        $finder = new Finder();

        // Skip vendor directories
        $output->writeln("Scanning for files: " . join(', ', array_keys($parsers)));
        $output->writeln("Skipping vendor dir as we haven't found any use for it.");
        $finder->files()->in($location)->notPath("vendor");

        // List of messages generated by each grader.
        $messages = new MessageList();

        /* @var ParserInterface $parser */
        foreach ($parsers as $key => $parser) {
          $output->writeln("Running parser: " . $key);
            /* @var Finder $finderInstance */
            $finderInstance = clone $finder;
            $parser->run($finderInstance, $messages);
        }

        $outputDir = $input->getOption('output-dir');
        $serve = $input->getOption('serve');
        $format = $input->getOption('format');

        if ($format == 'console') {
            $formatter = new ConsoleFormatter();
        }
        else if ($format == 'yaml') {
            $formatter = new YamlFormatter();
        }

        if ($serve) {
          $output->writeln("Setting '--format' to angular");
           $format = 'angular';
           if (is_null($outputDir)) {
             $tempfile=tempnam(sys_get_temp_dir(),'phpgrade-');
             if (file_exists($tempfile)) {
               unlink($tempfile);
             }
             mkdir($tempfile);
             $outputDir = $tempfile;
             $output->writeln("Created temporary '--output-dir' directory as none given: " . $outputDir);
           }
        }

        if ($input->getOption('verbose')) {
            $output->writeln("Output dir: $outputDir");
            $output->writeln("Format    : $format");
        }

        if ($formatter == 'angular') {
            $formatter = new AngularFormatter();
            $formatter->format($messages->getMessages(), $output, $serve);
        }else{
            $formatter->format($messages->getMessages());
        }

    }
}
