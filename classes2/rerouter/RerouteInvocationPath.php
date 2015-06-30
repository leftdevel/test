<?php

require_once(__DIR__.'/RerouteListenerInterface.php');
require_once(__DIR__.'/MaintenanceModeListener.php');
require_once(__DIR__.'/LandingPageListener.php');

class RerouterInvocationPath
{
    private $listeners;

    public function __construct()
    {
        $this->listeners = array();

        // Ensure contract violation can be catch on instantiation
        $this->addListener(new MaintenanceModeListener());
        $this->addListener(new LandingPageListener());
    }

    public function addListener(RerouteListenerInterface $listener)
    {
        $this->listeners[] = $listener;
    }

    public function handle($invocationPath)
    {
        foreach ($this->listeners as $listener) {
            if ($url = $listener->handle($invocationPath)) {
                return $url;
            }
        }

        return NULL;
    }
}
