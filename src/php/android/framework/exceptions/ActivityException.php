<?php

namespace php\android\framework\exceptions;


use Exception;
use php\android\framework\activity\AbstractActivity;

class ActivityException extends \Exception
{
    private $activity;

    /**
     * ActivityException constructor.
     * @param AbstractActivity $activity
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(AbstractActivity $activity, $message = "", $code = 0, Exception $previous = null)
    {
        $this->activity = $activity;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return AbstractActivity
     */
    public function getActivity(): AbstractActivity
    {
        return $this->activity;
    }

}