<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 27-6-14
 * Time: 10:20
 */

namespace PhpGrade;


class Config {
    private $phpcsWarningLevel = 5;
    private $phpcsErrorLevel = 5;

    private $phpcpdMinLines = 5;
    private $phpcpdMinTokens = 70;
    private $phpcpdFuzzyVariableMatching = true;

    /**
     * @return int
     */
    public function getPhpcsErrorLevel()
    {
        return $this->phpcsErrorLevel;
    }

    /**
     * @return int
     */
    public function getPhpcsWarningLevel()
    {
        return $this->phpcsWarningLevel;
    }

    /**
     * @return boolean
     */
    public function isPhpcpdFuzzyVariableMatching()
    {
        return $this->phpcpdFuzzyVariableMatching;
    }

    /**
     * @return int
     */
    public function getPhpcpdMinLines()
    {
        return $this->phpcpdMinLines;
    }

    /**
     * @return int
     */
    public function getPhpcpdMinTokens()
    {
        return $this->phpcpdMinTokens;
    }

} 