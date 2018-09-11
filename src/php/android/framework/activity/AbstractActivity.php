<?php

namespace php\android\framework\activity;

use php\android\UXAppBar;
use php\android\UXToast;
use php\android\UXView;

abstract class AbstractActivity extends UXView
{
    /**
     * @return string
     */
    abstract public function getName() : string;

    /**
     * @param UXAppBar $appBar
     */
    abstract public function onUpdateAppBar(UXAppBar $appBar) : void;

    public function __construct()
    {
        parent::__construct($this->getName() ?? "home");
        $this->setOnUpdateAppBar([$this, "onUpdateAppBar"]);
    }

    /**
     * @param string $msg
     * @param int $duration
     * @return UXToast
     */
    public function toast(string $msg, int $duration = 1000) : UXToast
    {
        $toast = new UXToast();
        $toast->text = $msg;
        $toast->duration = $duration;
        $this->show();

        return $toast;
    }
}