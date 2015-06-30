<?php

//...

class AppUser_LoginDelegate
{
    private $invocationPath = '';

    //...

    function rerouteInvocationPath($invocationPath)
    {
        $this->invocationPath = $invocationPath;

        if ($url = $this->rerouteForMaintenanceMode()) {
            return $url;
        } elseif ($url = $this->rerouteForLandingPage()) {
            return $url;
        }

        return NULL;
    }

    private function rerouteForMaintenanceMode()
    {
        $maintenanceMode = constant(VirtualTourApp::MAINTENANCE_MODE_DEF_NAME);
        if (VirtualTourApp::maintenanceModeBreakthroughEnabled())
        {
            $maintenanceMode = VirtualTourApp::MAINTENANCE_MODE_DISABLED;
        }
        switch ($maintenanceMode) {
        case VirtualTourApp::MAINTENANCE_MODE_DISABLED:
            // normal operational mode; do nothing special
            break;
        case VirtualTourApp::MAINTENANCE_MODE_FULL:
            return "public/pages/maintenance_full";
            break;
        case VirtualTourApp::MAINTENANCE_MODE_READONLY:
            $uri = $_SERVER['REQUEST_URI'];
            if (
                    strncmp($uri, '/panel/',        7 ) === 0     // backend proper
                 or strncmp($uri, '/client/',       7 ) === 0     // internal admin
                 or strncmp($uri, '/admin/',        6 ) === 0     // internal admin
                 or strncmp($uri, '/login',         6 ) === 0     // backend login
                 or strncmp($uri, '/public/api',    11) === 0     // API
             )
            {
                return "public/pages/maintenance_readonly";
            }
            break;
        default:
            throw new Exception("Unexpected MAINTENANCE_MODE '{$maintenanceMode}'");
        }

        return NULL;
    }

    private function rerouteForLandingPage()
    {
        if (strpos($invocationPath, '/') === false and preg_match('/^[A-z][A-z-_0-9]*$/', $invocationPath) and LandingPagePeer::retrieveBySlug($invocationPath)) {
            // the skin system will only show the tourbuzzBranded skin if the REQUEST_URI matches a known "tourbuzz" branded page
            $_SERVER['REQUEST_URI'] = "/public/pages/landing/{$invocationPath}";
            return "public/pages/landing/{$invocationPath}";
        }

        return NULL;
    }
}
