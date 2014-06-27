<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 27-6-14
 * Time: 11:07
 */

namespace PhpGrade;


class MessageList {
    private $messages = array();

    public function addMessages($messages){
        foreach($messages as $lineNr => $messageList){
            if(isset($this->messages[$lineNr])){
                $this->messages[$lineNr] = array_merge($this->messages[$lineNr], $messageList);
            }else{
                $this->messages[$lineNr] = $messageList;
            }
        }
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        ksort($this->messages);
        return $this->messages;
    }

} 