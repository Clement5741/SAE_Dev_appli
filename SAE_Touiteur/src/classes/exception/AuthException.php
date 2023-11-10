<?php
namespace App\classes\exception;

class AuthException extends \Exception
{

    /**
     * @param string $string
     */
    public function __construct(string $string)
    {
        parent::__construct("<h2>" . $string . "</h2>");
    }
}