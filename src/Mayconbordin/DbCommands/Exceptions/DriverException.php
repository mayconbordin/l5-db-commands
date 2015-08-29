<?php namespace Mayconbordin\DbCommands\Exceptions;

class DriverException extends \RuntimeException
{
    /**
     * Get the message of the exception and, if exists, the underlying message from the previous exception.
     * @return string
     */
    public function getFullMessage()
    {
        $message = $this->getMessage();

        if ($this->getPrevious() != null) {
            $message .= sprintf("Error %s: %s.", $this->getPrevious()->getCode(), $this->getPrevious()->getMessage());
        }

        return $message;
    }
}