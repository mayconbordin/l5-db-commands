<?php namespace Mayconbordin\DbCommands\Exceptions;

class DriverException extends \RuntimeException
{
    protected $sql;

    /**
     * @param string $message
     * @param string $sql
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message = "", $sql = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->sql = $sql;
    }

    /**
     * Get the message of the exception and, if exists, the underlying message from the previous exception.
     * @return string
     */
    public function getFullMessage()
    {
        $message = $this->getMessage();

        if ($this->getPrevious() != null) {
            $message .= sprintf("\nError %s: %s.", $this->getPrevious()->getCode(), $this->getPrevious()->getMessage());
        }

        if (strlen($this->sql) > 0) {
            $message .= sprintf("\nSQL %s.", $this->sql);
        }

        return $message;
    }
}