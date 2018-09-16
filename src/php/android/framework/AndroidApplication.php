<?php

namespace php\android\framework;


use php\android\framework\activity\AbstractActivity;
use php\android\framework\exceptions\ActivityException;
use php\android\UXAppBar;
use php\android\UXMobileApplication;
use php\android\UXStatusBar;
use php\io\IOException;

class AndroidApplication
{
    /**
     * @var AndroidApplication
     */
    private static $instance;

    /**
     * @var AbstractActivity[]
     */
    private $activities;

    /**
     * @var string
     */
    private $mainActivity;

    /**
     * AndroidApplication constructor.
     */
    public function __construct() {
        static::$instance = $this;
    }

    /**
     * @return AndroidApplication
     */
    public static function get() : AndroidApplication {
        return static::$instance;
    }


    /**
     * @param AbstractActivity $activity
     * @param bool $registerInRuntime
     * @throws ActivityException
     */
    public function registerActivity(AbstractActivity $activity, bool $registerInRuntime = false) : void {
        if ($this->activities[$activity->getName()])
            throw new ActivityException($activity, "Activity registered!");

        $this->activities[$activity->getName()] = $activity;

        if ($registerInRuntime)
            UXMobileApplication::addView($activity->getName(), $activity);
    }


    /**
     * @param string $name
     * @return AbstractActivity
     * @throws IOException
     */
    public function getActivity(string $name) : AbstractActivity {
        if (!$this->activities[$name])
            throw new IOException("Activity {$name} not found!");

        return $this->activities[$name];
    }

    /**
     * @return AbstractActivity[]
     */
    public function getActivities(): array {
        return $this->activities;
    }

    /**
     * @return string
     */
    public function getMainActivity() : string {
        return $this->mainActivity;
    }


    /**
     * @param string $mainActivity
     * @throws IOException
     */
    public function setMainActivity(string $mainActivity) : void {
        if (!$this->activities[$mainActivity])
            throw new IOException("Activity {$mainActivity} not found!");

        $this->mainActivity = $mainActivity;
    }

    /**
     * @param $activityName
     */
    public function show($activityName) : void
    {
        UXMobileApplication::showView($activityName);
    }

    /**
     * @return UXAppBar
     */
    public function getAppBar() : UXAppBar {
        return UXMobileApplication::getAppBar();
    }

    /**
     * @return UXStatusBar
     */
    public function getStatusBar() : UXStatusBar {
        return UXMobileApplication::getStatusbar();
    }

    /**
     * Launch mvc android app
     */
    public function launch(callable $callback = null) : void {
        foreach ($this->activities as $name => $activity)
        {
            try {
                UXMobileApplication::addView($name, $activity);
            } catch (\Exception $e) {
                continue;
            }
        }

        if (!$this->mainActivity)
            throw new IOException("Main activity not found!");

        $this->show($this->mainActivity);

        if (is_callable($callback))
            call_user_func($callback, $this->getActivity($this->mainActivity));
    }
}