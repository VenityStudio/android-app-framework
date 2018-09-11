<?php

namespace php\android\framework\activity;

use php\android\UXAppBar;
use php\android\UXToast;
use php\android\UXView;
use php\gui\UXLoader;
use php\io\Stream;
use php\lib\str;

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

    public function __construct() {
        parent::__construct($this->getName() ?? "home");
        $this->setOnUpdateAppBar([$this, "onUpdateAppBar"]);
        $this->loadFXML(str::replace(get_class($this), "\\", "/") . ".fxml");
    }

    /**
     * @param string $msg
     * @param int $duration
     * @return UXToast
     */
    public function toast(string $msg, int $duration = 1000) : UXToast {
        $toast = new UXToast();
        $toast->text = $msg;
        $toast->duration = $duration;
        $this->show();

        return $toast;
    }

    /**
     * @param string $path
     */
    protected function loadFXML(string $path)
    {
        Stream::tryAccess($path, function (Stream $stream) {
            try {
                $this->center = (new UXLoader())->load($stream);
            } catch (\Exception $exception) {
                echo "Error load from fxml: {$exception->getMessage()}\n";
            }
        });
    }
}