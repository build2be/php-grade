<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 27-6-14
 * Time: 11:07
 */

namespace PhpGrade;


class MessageList
{
    private $messages = array();

    public function addMessages($file, $messages)
    {
        foreach ($messages as $lineNr => $messageList) {
            if (isset($this->messages[$file][$lineNr])) {
                $this->messages[$file][$lineNr] = array_merge($this->messages[$file][$lineNr], $messageList);
            } else {
                $this->messages[$file][$lineNr] = $messageList;
            }
        }
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        $result = array();
        foreach ($this->messages as $file => $lines) {
            ksort($lines);
            $result[$file] = $lines;
        }
        return $result;
    }

} 