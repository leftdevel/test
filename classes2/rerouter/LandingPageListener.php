<?php

require_once(__DIR__.'/RerouteListenerInterface.php');

class LandingPageListener implements RerouteListenerInterface
{
    public function handle($invocationPath)
    {
        if (strpos($invocationPath, '/') === false and preg_match('/^[A-z][A-z-_0-9]*$/', $invocationPath) and LandingPagePeer::retrieveBySlug($invocationPath)) {
            // the skin system will only show the tourbuzzBranded skin if the REQUEST_URI matches a known "tourbuzz" branded page
            $_SERVER['REQUEST_URI'] = "/public/pages/landing/{$invocationPath}";
            return "public/pages/landing/{$invocationPath}";
        }

        return NULL;
    }
}
