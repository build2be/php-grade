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
} 