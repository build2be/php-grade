<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 27-6-14
 * Time: 10:29
 */

namespace PhpGrade\Parsers;


use PhpGrade\Config;
use PhpGrade\Message;
use PhpGrade\MessageList;
use SebastianBergmann\PHPCPD\Detector\Detector;
use SebastianBergmann\PHPCPD\Detector\Strategy\DefaultStrategy;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

class PhpCpdParser extends BaseParser implements ParserInterface
{

    public function run(Finder $iterator, MessageList &$messageList)
    {
        $detector = new Detector(new DefaultStrategy(), null);
        $config = new Config();
        $clones = $detector->copyPasteDetection(
            $iterator->name("*.php"),
            $config->getPhpcpdMinLines(),
            $config->getPhpcpdMinTokens(),
            $config->isPhpcpdFuzzyVariableMatching()
        );

        if (count($clones) == 0) {
            return null;
        }
        foreach ($clones as $clone) {
            foreach ($clone->getFiles() as $file) {
                $filename = (string) $file->getName();
                $lineNo = $file->getStartLine();
                $messageObject = new Message('phpcpd');
                $messageObject->setLine($lineNo);
                $messageObject->setErrorLevel(Message::LEVEL_INFO);
                $messageObject->setMessage('Code duplication');
                $result[$lineNo][] = $messageObject;
                $messageList->addMessages($filename, $result);
            }
        }
    }
}
