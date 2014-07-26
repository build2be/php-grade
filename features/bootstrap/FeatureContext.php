<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Behat context class.
 */
class FeatureContext implements SnippetAcceptingContext
{
    /* @var array $output */
    var $output;

    /* @var string $lastLine */
    var $lastLine;
    /**
     * Initializes context.
     *
     * Every scenario gets it's own context object.
     * You can also pass arbitrary arguments to the context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @When I run ":arg1"
     */
    public function iRun($arg1)
    {
        $this->iRunWith($arg1, null);
    }

    /**
     * @When I run ":arg1" with ":arg2"
     */
    public function iRunWith($arg1, $arg2)
    {
        $output = $this->getOutput();
        $command = "php $arg1";
        if (!is_null($arg2)) {
          $command .= " " . $arg2;
        }
        // $result is last line
        $result = exec($command, $output);
        $this->setOutput($output);
    }

    /**
     * @Then STDOUT should contain:
     */
    public function stdoutShouldContain(PyStringNode $string, $expected = true)
    {
        $out = $this->getRaw();
        if (empty($out)) {
          throw new \Exception("Empty result");
        }
        $raw = $string->getRaw();
        $found = strpos($out, $raw);
        if (($found === false && $expected) || ($found !== false && !$expected)) {
            var_dump(array('expected' => $expected, 'found'=>$found));
            var_dump($out);
            throw new \Exception("Unexpected string in output");
        };
    }

    /**
     * @Then STDOUT should not contain:
     */
    public function stdoutShouldNotContain(PyStringNode $string)
    {
      $this->stdoutShouldContain($string, false);
    }

    /**
     * @return array
     */
    public function &getOutput() {
        return $this->output;
    }

    /**
     * @param array $output
     */
    public function setOutput($output) {
        $this->output = $output;
    }

    /**
     * Returns raw string.
     *
     * @return string
     */
    public function getRaw()
    {
      return implode("\n", $this->getOutput());
    }

    /**
     * @return string
     */
    public function getLastLine() {
      return $this->lastLine;
    }

    /**
     * @param string $lastLine
     */
    public function setLastLine($lastLine) {
      $this->lastLine = $lastLine;
    }

}
