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
use SensioLabs\Security\SecurityChecker;
use Symfony\Component\Finder\Finder;

class PhpSecurityParser extends BaseParser implements ParserInterface
{

    public function run(Finder $iterator, MessageList &$messageList)
    {
        $composerIterator = $iterator->name("composer.lock");
        $composerLockFiles = iterator_to_array($composerIterator, false);
        if (count($composerLockFiles) > 0) {
            $checker = new SecurityChecker();
            $report = $checker->check((string) $composerLockFiles[0], 'json');
            $report = json_decode($report, true);

            $jsonFile = str_replace('composer.lock', 'composer.json', $composerLockFiles[0]);

            $composerJson = file($jsonFile);
            $result = array();
            foreach ($report as $name => $info) {
                foreach ($composerJson as $lineNr => $line) {
                    if (strpos($line, $name) !== false) {

                        foreach ($info['advisories'] as $aInfo) {
                            $messageObject = new Message('security');
                            $messageObject->setLine($lineNr + 1);
                            $messageObject->setErrorLevel(Message::LEVEL_ERROR);
                            $message = $aInfo['title'] . ' (' . $aInfo['link'] . ')' . PHP_EOL;
                            $messageObject->setMessage($message);
                            $result[$lineNr][] = $messageObject;
                        }
                    }
                }
            }

            $messageList->addMessages($jsonFile, $result);
        }
    }
}
